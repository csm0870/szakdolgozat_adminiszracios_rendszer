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
                                                            'contain' => ['ThesisTopics' => ['Students']]]);
        $this->set(compact('offeredTopics'));
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
            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
            
            if(!$user->has('internal_consultant')){
                $this->Flash->error(__('Nem belső konzulensként van bejelentkezve.'));
            }else{
                $offeredTopic->internal_consultant_id = $user->internal_consultant->id;
                if ($this->OfferedTopics->save($offeredTopic)) {
                    $this->Flash->success(__('Mentés sikeres.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Mentés sikertelen.'));
            }
        }
        
        $this->set(compact('offeredTopic'));
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
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        if(empty($offeredTopic)){ //Ha nem létezik a téma
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem létezik.'));
        }elseif($user->internal_consultant->id != $offeredTopic->internal_consultant_id){ //Ha nem az adott belső konzulenshez tartozik
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem Önhöz tartozik.'));
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == 5){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma foglalásának véglegesítését még nem tette meg a hallgató.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        if($this->request->is(['patch', 'post', 'put'])){
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            
            if($this->OfferedTopics->save($offeredTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $offeredTopic->id]);
            }
            $this->Flash->error(__('Mentés sikertelen.'));
        }
        
        $this->set(compact('offeredTopic'));
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
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        if($user->internal_consultant->id != $offeredTopic->internal_consultant_id){
            $this->Flash->error(__('A téma nem Önhöz tartozik.'));
            return $this->redirect(['action' => 'index']);
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == 5){ //Ha téma foglalva van és a hallgató véglegesítésére vár
            $ok = false;
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma foglalásának véglegesítését még nem tette meg a hallgató.'));
        }
        
        if ($this->OfferedTopics->delete($offeredTopic)){
            //Ha a témaajánlatot valaki választotta és belső konzulens döntésre vár a foglalás
            if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == 2){
                $offeredTopic->thesis_topic->thesis_topic_status_id = 3;
                $this->OfferedTopics->ThesisTopics->save($offeredTopic->thesis_topic);
            }
            
            $this->Flash->success(__('Törlés sikeres.'));
        }else{
            $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function acceptBooking($id = null){
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id],
                                                           'contain' => ['ThesisTopics']])->first();
        
        $ok = true;
        if($user->internal_consultant->id != $offeredTopic->internal_consultant_id){ //Ha nem az adott belső konzulenshez tartozik
            $ok = false;
            $this->Flash->error(__('A téma nem fogadható el.') . ' ' . __('A téma nem Önhöz tartozik.'));
        }elseif($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id != 2){ //Ha téma belső konzulensi elfogadásra vár
            $ok = false;
            $this->Flash->error(__('A téma nem fogadható el.') . ' ' . __('A téma foglalása nem belső konzulensi elfogadásra vár.'));
        }
    }
}
