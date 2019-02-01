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
                                                                           'deleted !=' => true, 'thesis_topic_status_id !=' => 1 /* Már biztosan véglegesített*/],
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
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['ThesisTopicStatuses']])->first();
    
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [3, 5, 7, 8, 10, 11])){ //Ha bírálati folyamatban van
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('Az bírálata még folyamatban van.'));
            return $this->redirect(['action' => 'index']);
        }

        if(!$ok) return $this->redirect(['action' => 'index']);

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
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [8, 9, 10, 11, 12])){ //Nem "A téma nem elfogadott", nem "Diplomakurzus sikertelen, tanaszékvezető döntésére vár", nem "Első diplomakurzus teljesítve", vagy nem "Elutsítva (első diplomakurzus sikertelen)" státuszban van
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.');
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

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesisTopic_id], 'contain' => ['ThesisTopicStatuses']])->first();

            $error_msg = '';
            $no_thesis_topic = false;
            
            if(empty($thesisTopic)){ //Nem létezik a téma
                $error_msg = __('A témáról nem dönthet.') . ' ' . __('Nem létező téma.');
                $no_thesis_topic = true;
            }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
                $error_msg = __('A témáról nem dönthet.') . ' ' . __('A témának nem Ön a belső konzulense.');
                $no_thesis_topic = true;
            }elseif($thesisTopic->thesis_topic_status_id != 2){ //Nem "A téma belső konzulensi döntésre vár" státuszban van
                $error_msg = __('A témáról nem dönthet.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.';
                $no_thesis_topic = true;
            }
            
            if($no_thesis_topic){
                $this->Flash->error($error_msg);
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
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['ThesisTopicStatuses']])->first();

        $error_msg = '';
        $no_thesis_topic = false;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('Nem létező téma.');
            $no_thesis_topic = true;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('A témának nem Ön a belső konzulense.');
            $no_thesis_topic = true;
        }elseif($thesisTopic->thesis_topic_status_id != 8){ //Nem "A téma elfogadott" státuszban van
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.';
            $no_thesis_topic = true;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if(empty($thesisTopic)){
            $no_thesis_topic = true;
            $this->set(compact('no_thesis_topic', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->getRequest()->getData());
            
            $first_thesis_subject_completed = $this->getRequest()->getData('first_thesis_subject_completed');
            
            if($first_thesis_subject_completed === null || !in_array($first_thesis_subject_completed, [0, 1])){
                $thesisTopic->setError('custom', __('A döntésnek "0"(nem) vagy "1"(igen) értéket kell felvennie!'));
            }else{
                if($first_thesis_subject_completed == 0){ //Első diplomakurzus sikertelen
                    $thesisTopic->thesis_topic_status_id = 9; //Téma elutasítva (első diplomakurzus sikertelen)
                }else{ //Első diplomakurzus sikeres
                    $thesisTopic->thesis_topic_status_id = 11; //Első diplomakurzus teljesítve
                    $thesisTopic->first_thesis_subject_failed_suggestion = null; // Javaslat nullázása
                }
            }
            
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
