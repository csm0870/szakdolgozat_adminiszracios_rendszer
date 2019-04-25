<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OfferedTopics Controller
 *
 * @property \App\Model\Table\OfferedTopicsTable $OfferedTopics
 *
 * @method \App\Model\Entity\OfferedTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OfferedTopicsController extends AppController
{
    /**
     * Belső konzulens kiírt témáinak listája
     *
     * @return \Cake\Http\Response|void
     */
    public function index(){
        $offeredTopics = $this->OfferedTopics->find('all', ['contain' => ['InternalConsultants', 'ThesisTopics' => ['Students', 'ThesisTopicStatuses']]]);
        $this->loadModel('Information');
        $information = $this->Information->find('all')->first();
        $this->set(compact('offeredTopics', 'information'));
    }

     /**
     * Hozzáadás
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        $offeredTopic = $this->OfferedTopics->newEntity();
        if ($this->request->is('post')) {
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            
            if($this->OfferedTopics->save($offeredTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Mentés sikertelen.'));
        }
        
        $languages = $this->OfferedTopics->Languages->find('list');
        $internalConsultants = $this->OfferedTopics->InternalConsultants->find('list');
        $this->set(compact('offeredTopic', 'languages', 'internalConsultants'));
    }
    
    /**
     * Szerkesztés
     *
     * @param string|null $id Offered Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['ThesisTopics']])->first();
        
        
        $ok = true;
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem létezik.'));
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma foglalásának véglegesítését még nem tette meg a hallgató.'));
        }elseif($offeredTopic->has('thesis_topic') && !in_array($offeredTopic->thesis_topic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                                                                      \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                      \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent')])){
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma le van foglalva.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        if($this->request->is(['patch', 'post', 'put'])){
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            
            //Belső konzulenst nem módosíthat
            unset($offeredTopic->internal_consultant_id);
            
            if($this->OfferedTopics->save($offeredTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $offeredTopic->id]);
            }
            $this->Flash->error(__('Mentés sikertelen.'));
        }
        
        $internalConsultants = $this->OfferedTopics->InternalConsultants->find('list');
        $languages = $this->OfferedTopics->Languages->find('list');
        $this->set(compact('offeredTopic', 'languages', 'internalConsultants'));
    }

    /**
     * Törlés
     *
     * @param string|null $id Offered Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['ThesisTopics']])->first();
        
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma nem törölhető.') . ' ' . __('A téma nem létezik.'));
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem törölhető.') . ' ' . __('A téma foglalásának véglegesítését még nem tette meg a hallgató.'));
        }elseif($offeredTopic->has('thesis_topic') && !in_array($offeredTopic->thesis_topic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                                                                      \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                      \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent')])){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem törölhető.') . ' ' . __('A téma le van foglalva.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        if ($this->OfferedTopics->delete($offeredTopic)){
            //Ha a témaajánlatot valaki választotta és belső konzulens döntésre vár a foglalás
            if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){
                $offeredTopic->thesis_topic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant');
                $this->OfferedTopics->ThesisTopics->save($offeredTopic->thesis_topic);
            }
            
            $this->Flash->success(__('Törlés sikeres.'));
        }else{
            $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Kiírt téma részletek
     * 
     * @param type $id
     * @return type
     */
    public function details($id = null){
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['Languages', 'ThesisTopics' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels']],
                                                                         'InternalConsultants']])->first();
        
        $ok = true;
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma részletei nem elérhetőek.') . ' ' . __('A téma nem létezik.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('offeredTopic'));
    }
}
