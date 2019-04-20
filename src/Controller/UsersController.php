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
        $this->Auth->allow(['login', 'logout']);
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
    
    public function login($group_type = null){
        if(!in_array($group_type, [1, 2])){
            $this->Flash->error(__('Nem létező belépési típus.!'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
        
        $login_text = "";
        if($group_type == 1) $login_text = __('Hallgatói belépés');
        elseif($group_type == 2) $login_text = 'Belépés';
        
        $this->set(compact('login_text'));
        
        $this->_doLogin($group_type);
    }
    
    public function logout() {
        $logout_redirect = $this->Auth->logout();
        $this->getRequest()->getSession()->destroy();
        $this->Flash->success(__('Sikeresen kijelentkeztél.'));
        return $this->redirect($logout_redirect);
    }
    
    private function _doLogin($group_type = null){
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            //A csoport típusának megfelelő felhasználótípusok ID-jai
            $group_ids = ($group_type == 1 ? [6] : [1, 2, 3, 4, 5, 7, 8]);
            
            if (!empty($user) && in_array($user['group_id'], $group_ids)){
                $this->Auth->setUser($user);
                
                if($user['group_id'] == 1) // Admin
                    return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => 'admin']);
                elseif($user['group_id'] == 2) // Belső konzulens
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'internal_consultant']);
                elseif($user['group_id'] == 3) // Tanszékvezető
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'head_of_department']);
                elseif($user['group_id'] == 4) // Témakezelő
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'topic_manager']);
                elseif($user['group_id'] == 5) // Szakdolgozatkezelő
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'thesis_manager']);
                elseif($user['group_id'] == 6) // Hallgató
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'student']);
                elseif($user['group_id'] == 7) // Bíráló
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'reviewer']);
                elseif($user['group_id'] == 8) // Záróvizsga összeállító
                    return $this->redirect(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'final_exam_organizer']);
                
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->error(__('Helytelen email vagy jelszó. Próbálja újra!'));
            $this->Auth->logout();
        }
    }
}
