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

        //Akkor törölheti, ha már nincs bírálati folyamatban
        if(in_array($thesisTopic->thesis_topic_status_id, [3, 5, 7, 8])){
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
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                          'thesis_topic_status_id' => 8], //Belső konzulenshez tartozik és elfogadott
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels']]])->first();
    
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma részletei nem elérhetők. Vagy nem létezik, vagy nem Ön a belső konzulense.'));
            return $this->redirect(['action' => 'index']);
        }
        
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
}
