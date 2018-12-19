<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Groups']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups', 'Reviewers', 'InternalConsultants', 'Students']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $reviewers = $this->Users->Reviewers->find('list', ['limit' => 200]);
        $this->set(compact('user', 'groups', 'reviewers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Reviewers']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $reviewers = $this->Users->Reviewers->find('list', ['limit' => 200]);
        $this->set(compact('user', 'groups', 'reviewers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function login($group_id = null){
        if(!in_array($group_id, [1, 2, 3, 4, 5, 6])){
            $this->Flash->error(__('Nem létező felhasználótípus!'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
        
        $group_name = "";
        if($group_id == 1) $group_name = __('Adminisztrátori');
        elseif($group_id == 2) $group_name = __('Belső konzulensi');
        elseif($group_id == 3) $group_name = __('Tanszékvezetői');
        elseif($group_id == 4) $group_name = __('Témakezelői');
        elseif($group_id == 5) $group_name = __('Szakdolgozatkezelői');
        elseif($group_id == 6) $group_name = __('Hallgatói');
        
        $this->set(compact('group_name'));
        
        $this->_doLogin($group_id);
    }
    
    public function logout() {
		$logout_redirect = $this->Auth->logout();
		$this->getRequest()->getSession()->destroy();
		return $this->redirect($logout_redirect);
    }
    
    private function _doLogin($group_id = null){
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (!empty($user) && $user['group_id'] == $group_id) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->error(__('Helytelen email vagy jelszó!'));
            $this->Auth->logout();
        }
    }
}
