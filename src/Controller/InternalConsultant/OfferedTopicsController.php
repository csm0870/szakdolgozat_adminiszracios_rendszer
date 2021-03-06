<?php
namespace App\Controller\InternalConsultant;

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
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $offeredTopics = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.internal_consultant_id' => ($user->has('internal_consultant') ? $user->internal_consultant->id : '-1')],
                                                            'contain' => ['ThesisTopics' => ['Students', 'ThesisTopicStatuses']]]);
        $this->loadModel('Information');
        $information = $this->Information->find('all')->first();
        $this->set(compact('offeredTopics', 'information'));
    }

    /**
     * Kiírt téma hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        $offeredTopic = $this->OfferedTopics->newEntity();
        if ($this->request->is('post')) {
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
            
            if(!$user->has('internal_consultant')){
                $this->Flash->error(__('Nem belső konzulensként van bejelentkezve.'));
            }else{
                $offeredTopic->internal_consultant_id = $user->internal_consultant->id;
                if($this->OfferedTopics->save($offeredTopic)){
                    $this->Flash->success(__('Mentés sikeres.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Mentés sikertelen.'));
            }
        }
        
        $languages = $this->OfferedTopics->Languages->find('list');
        $this->set(compact('offeredTopic', 'languages'));
    }

    /**
     * Kiírt téma szerkesztés
     *
     * @param string|null $id Kiírt téma egyedi azonosítója
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['ThesisTopics']])->first();
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem létezik.'));
        }elseif($user->internal_consultant->id != $offeredTopic->internal_consultant_id){ //Ha nem az adott belső konzulenshez tartozik
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem Önhöz tartozik.'));
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma foglalásának véglegesítését még nem tette meg a hallgató.'));
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
        
        $languages = $this->OfferedTopics->Languages->find('list');
        $this->set(compact('offeredTopic', 'languages'));
    }

    /**
     * Kiírt téma törlése
     *
     * @param string|null $id Kiírt téma egyedi azonosítója
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['ThesisTopics']])->first();
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma nem törölhető.') . ' ' . __('A téma nem létezik.'));
        }elseif($user->internal_consultant->id != $offeredTopic->internal_consultant_id){
            $this->Flash->error(__('A téma nem törölhető.') . ' ' . __('A téma nem Önhöz tartozik.'));
            return $this->redirect(['action' => 'index']);
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
     * Kiírt téma részletei
     * 
     * @param type $id Kiírt téma egyedi azonosítója
     * @return type
     */
    public function details($id = null){
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['Languages', 'ThesisTopics' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'], 'ThesisTopicStatuses']]])->first();
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma részletei nem elérhetőek.') . ' ' . __('A téma nem létezik.'));
        }elseif($user->internal_consultant->id != $offeredTopic->internal_consultant_id){ //Ha nem az adott belső konzulenshez tartozik
            $ok = false;
            $this->Flash->error(__('A téma részletei nem elérhetőek.') . ' ' . __('A téma nem Önhöz tartozik.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('offeredTopic'));
    }
    
    /**
     * Foglalás elfogadása/elutasítása
     * 
     * @return type
     */
    public function acceptBooking(){
        if($this->getRequest()->is('post')){
            $offered_topic_id = $this->getRequest()->getData('offered_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);

            $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $offered_topic_id],
                                                               'contain' => ['ThesisTopics']])->first();

            $ok = true;
            
            if(empty($offeredTopic)){ //Nem létezik a téma
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem létező téma.'));
            }elseif($offeredTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('A témának nem Önhöz tartozik.'));
            }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){ //Nem "A témafoglalás belső konzulensi döntésre vár" státuszban van
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem belső konzulens döntésére vár.'));
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
            
            if($accepted == 0){
                $offeredTopic->thesis_topic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant');
                $offeredTopic->thesis_topic->offered_topic_id = null;
            }else{
                $offeredTopic->thesis_topic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking');
            }

            if($this->OfferedTopics->ThesisTopics->save($offeredTopic->thesis_topic)) $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            else $this->Flash->error(($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!'));
        }
        
        return $this->redirect(['action' => 'index']);
    }
}
