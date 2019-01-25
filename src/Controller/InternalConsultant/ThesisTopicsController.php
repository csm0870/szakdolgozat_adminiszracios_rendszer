<?php
namespace App\Controller\InternalConsultant;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') == 'setFirstThesisSubjectCompleted') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Belső konzulenshez tartozó témák listája
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak a véglegesített és a hozzá tartozó témákat látja
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['internal_consultant_id' => ($user->has('internal_consultant') ? $user->internal_consultant->id : null),
                                                                           'modifiable' => false, 'deleted !=' => true, 'thesis_topic_status_id !=' => 1 /* Már biztosan véglegesített*/],
                                                          'contain' => ['Students', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

        $this->set(compact('thesisTopics'));
    }

    /**
     * Téma törlése a belső konzulens által (nem tényleges fizikai törlés)
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->get($id);

        //Ha bírálati folyamatban van, akkor nem törölhető
        if(in_array($thesisTopic->thesis_topic_status_id, [1, 2, 4, 6])){
            $this->Flash->error(__('A téma nem törölhető. Az bírálata még folyamatban van.'));
            return $this->redirect(['action' => 'index']);
        }

        $thesisTopic->deleted = true;
        if ($this->ThesisTopics->save($thesisTopic)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen. Kérem próbálja újra!'));
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id
     */
    public function details($id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'], 'ThesisTopicStatuses']])->first();
    
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők. Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id == ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Véglegesítésre vár
            $this->Flash->error(__('A téma részletei nem elérhetők. A téma még nem véglegesített.'));
            $ok = false;
        }elseif($thesisTopic->modifiable === true || $thesisTopic->thesis_topic_status_id === 1){ //Véglegesítésre vár
            $this->Flash->error(__('A téma részletei nem elérhetők. A téma még nem véglegesített.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [8, 9])){ //Nem elfogadott, vagy nem diplomakurzus sikertelen státuszban van
            $this->Flash->error(__('A téma részletei nem elérhetők. A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.' );
            $ok = false;
        }
        
        if(!$ok) return $this->redirect (['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Táma elfogadása vagy elutasítása
     * @return type
     */
    public function accept(){
        if($this->getRequest()->is('post')){
            $thesisTopic_id = $this->getRequest()->getData('thesis_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->loadModel('Users');
            
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesisTopic_id, 'modifiable' => false,
                                                                              'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : null, //Belso konzulens a saját témája
                                                                              'thesis_topic_status_id' => 2 //Belső konzulensi döntésre vár
                                                                              ]])->first();

            if(empty($thesisTopic)){
                $this->Flash->error(__('Ezt a témát nem fogadhatja el. Már vagy döntést hozott, vagy nem Önhöz tartozik, vagy még nem véglegesített, vagy már el lett utasítva a téma!'));
                return $this->redirect(['action' => 'index']);
            }
            
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? 3 : 4;

            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!!'));
            }else{
                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Diplomakurzus első félévének teljesítésének rögzítése
     * 
     * @param type $id Téma azonosítója
     */
    public function setFirstThesisSubjectCompleted($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id, 'modifiable' => false,
                                                                          'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : null, //Belso konzulens a saját témája
                                                                          'thesis_topic_status_id' => 8,
                                                                          'first_thesis_subject_completed IS' => null //Még nem tudni, hogy teljesítette-e az első diplimakurzust
                                                                          ]])->first();

        $error_msg = '';
        $no_thesis_topic = false;
        //Ha a feltételeknek megfelelő téma nem található
        if(empty($thesisTopic)){
            $error_msg = __('Erről a témáról nem dönthet. Még elfogadási folyamatban van a téma, vagy nem Önhöz tartozik, vagy még nem véglegesített, vagy már el lett utasítva a téma, vagy már eldöntötte, hogy teljesítette az első diplomakurzust.');
            $no_thesis_topic = true;
            $this->set(compact('no_thesis_topic', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->getRequest()->getData());
            //Ha nincs teljesítve, akkor a javaslatot "NULL" értékre állítjuk
            if($thesisTopic->first_thesis_subject_completed === true) $thesisTopic->first_thesis_subject_failed_suggestion = null;
            elseif($thesisTopic->first_thesis_subject_completed === false) $thesisTopic->thesis_topic_status_id = 9;
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $thesisTopic->getErrors();
                if(!empty($errors)){
                    foreach($errors as $error){
                        if(is_array($error)){
                            foreach($error as $err){
                                $error_ajax.= '<br/>' . $err;
                            }
                        }else{
                            $error_ajax.= '<br/>' . $error;
                        }
                    }
                }
            }
        }
        
        $this->set(compact('thesisTopic' ,'no_thesis_topic', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
