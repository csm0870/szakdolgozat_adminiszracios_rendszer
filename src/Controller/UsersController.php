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
        $this->Auth->allow(['login', 'logout', 'studentRegistration']);
    }
    
    /**
     * Felhasználói belépés
     * 
     * @param type $group_type Felhaszbálói csoport száma (1 - hallgató, 2 - többi felhasználó)
     * @return type
     */
    public function login($group_type = null){
        if(!in_array($group_type, [1, 2])){
            $this->Flash->error(__('Nem létező belépési típus.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
		
        $login_text = "";
        if($group_type == 1) $login_text = __('Hallgatói belépés');
        elseif($group_type == 2) $login_text = __('Belépés');
        
        $this->set(compact('login_text', 'group_type'));
        
        $this->_doLogin($group_type);
    }
    
    /**
     * Felhasználói kijelentkezés
     * 
     * @return type
     */
    public function logout() {
        $logout_redirect = $this->Auth->logout();
        $this->getRequest()->getSession()->destroy();
        $this->Flash->success(__('Sikeresen kijelentkeztél.'));
        return $this->redirect($logout_redirect);
    }
    
    /**
     * Felhasználó beléptetése és átirányítása a megfelelő helyre
     * 
     * @param type $group_type Felhaszbálói csoport száma (1 - hallgató, 2 - többi felhasználó)
     * @return type
     */
    private function _doLogin($group_type = null){
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            //A csoport típusának megfelelő felhasználótípusok ID-jai
            $group_ids = ($group_type == 1 ? [6] : [1, 2, 3, 4, 5, 7, 8]);
            
            if(!empty($user) && in_array($user['group_id'], $group_ids)){
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
            
            $this->Flash->error(__('Helytelen felhasználónév vagy jelszó. Próbálja újra!'));
            $this->Auth->logout();
        }
    }
    
    /**
     * Hallgatói regisztráció
     */
    public function studentRegistration(){
        $user = $this->Users->newEntity();
        if($this->request->is('post')){
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            $password = $this->getRequest()->getData('password');
            $password_again = $this->getRequest()->getData('password_again');
            
            //Ha adott meg jelszót, akkor megnézzük, hogy megadta-e a jelszót újra, illetve megegyeznek-e
            if(!empty($password)){
                if(empty($password_again)) $user->setError('password_again', __('Adja meg a jelszót újra!'));
                elseif($password != $password_again){
                    $user->setError('password', __('A jelszavak nem egyeznek.'));
                    $user->setError('password_again', __('A jelszavak nem egyeznek.'));
                }
            }
            
            $user->group_id = 6;
            
            if($this->Users->save($user)){
                $this->Flash->success(__('Regisztráció sikeres.'));
                return $this->redirect(['action' => 'login', 1]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        $this->set(compact('user'));
    }
}
