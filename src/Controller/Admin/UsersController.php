<?php
namespace App\Controller\Admin;

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
    /**
     * Felhasználók listája
     */
    public function index(){
        $users = $this->Users->find('all', ['conditions' => ['group_id IN' => [1, 2, 3, 4, 5, 7, 8]], //Hallgató kivételével mindenki
                                            'contain' => ['Groups']]);
        $this->set(compact('users'));
    }

    /**
     * Felhasználó hozzáadása
     */
    public function add($group_id = null, $user_data_id = null){
        $user = $this->Users->newEntity();
        if($this->request->is('post')){
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            if(isset($user->group_id)){
                if($user->group_id == 6) $user->setError('group_id', __('Hallgatói felhasználói fiókot nem adhat hozzá.'));
                else{
                    //Jelszó mentése
                    $rawPassword = $this->Users->RawPasswords->find('all', ['conditions' => ['RawPasswords.user_id' => $user->id]])->first();
                    
                    if(empty($rawPassword)) $rawPassword = $this->Users->RawPasswords->newEntity();
                    
                    $password = $this->request->getData('password');
                    $rawPassword->password = empty($password) ? '' : $password;
                    $user->raw_password = $rawPassword;
                    
                    //Felhaszbálóhoz hozzárendeljük a belső konzulens vagy a bírálót, attól függően, hogy a felhasználói típus az-e
                    if($user->group_id == 2){
                        $internalConsultant = $this->Users->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $this->getRequest()->getData('internal_consultant_id')],
                                                                                              'contain' => ['Users']])->first();
                        if(empty($internalConsultant)) $user->setError('internal_consultant_id', __('A belső konzulens nem létezik.'));
                        elseif($internalConsultant->has('user')) $user->setError('internal_consultant_id', __('A belső konzulenshez már tartozik felhasználói fiók.'));
                        else $user->internal_consultant = $internalConsultant;
                    }elseif($user->group_id == 7){
                        $reviewer = $this->Users->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $this->getRequest()->getData('reviewer_id')],
                                                                          'contain' => ['Users']])->first();
                        if(empty($reviewer)) $user->setError('reviewer_id', __('A bíráló nem létezik.'));
                        elseif($reviewer->has('user')) $user->setError('reviewer_id', __('A bírálóhoz már tartozik felhasználói fiók.'));
                        else $user->reviewer = $reviewer;
                    }
                }
            }
            
            if($this->Users->save($user)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        //Ha a kérésben szerepel a group_id, akkor ellenőrízzük, hogy megfelelő-e a group_id és, hogy a hozzá tartozó user_data_id szerinti felhasználó létezik-e és van-e hozzá felhasználói fiók
        if(!empty($group_id)){
            if($group_id == 2){
                $internalConsultant = $this->Users->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $user_data_id],
                                                                                      'contain' => ['Users']])->first();
                if(empty($internalConsultant)) $this->Flash->error(__('A belső konzulenshez már tartozik felhasználi fiók.'));
                elseif($internalConsultant->has('user')) $this->Flash->error(__('A belső konzulenshez már tartozik felhasználói fiók.'));
            }elseif($group_id == 7){
                $reviewer = $this->Users->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $user_data_id],
                                                                  'contain' => ['Users']])->first();
                if(empty($reviewer)) $this->Flash->error( __('A bíráló nem létezik.'));
                elseif($reviewer->has('user')) $this->Flash->error(__('A bírálóhoz már tartozik felhasználói fiók.'));
            }else{
                $this->Flash->error(__('Csak belső konzulenshez és bírálóhoz adható hozzá felhasználói fiók.'));
                $group_id = null;
            }
        }
        
        $groups = $this->Users->Groups->find('list', ['conditions' => ['Groups.id IN' => [1, 2, 3, 4, 5, 7, 8]]]); //Hallgató kivételével mindenki
        $reviewers = $this->Users->Reviewers->find('list');
        $internalConsultants = $this->Users->InternalConsultants->find('list');
        $this->set(compact('user', 'groups', 'reviewers', 'internalConsultants', 'group_id', 'user_data_id'));
    }

    /**
     * Felhasználó módosítása
     *
     * @param string|null $id Felhasználó egyedi azonosítója
     */
    public function edit($id = null){
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $id],
                                                            'contain' => ['InternalConsultants', 'Reviewers']])->first();
        if(empty($user)){
            $this->Flash->error(__('Felhasználói fiók adatai nem szerkeszthetőel.') . ' ' . __('A felhasználói fiók nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->request->is(['patch', 'post', 'put'])){
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            if(isset($user->group_id)){
                if($user->group_id == 6) $user->setError('group_id', __('Hallgatói felhasználói fiókot nem adhat hozzá.'));
                else{
                    //Jelszó mentése, ha módosult
                    if($user->isDirty('password')){
                        $rawPassword = $this->Users->RawPasswords->find('all', ['conditions' => ['RawPasswords.user_id' => $user->id]])->first();

                        if(empty($rawPassword)) $rawPassword = $this->Users->RawPasswords->newEntity();

                        $password = $this->request->getData('password');
                        $rawPassword->password = empty($password) ? '' : $password;
                        $user->raw_password = $rawPassword;
                    }
                    
                    //Felhaszbálóhoz hozzárendeljük a belső konzulens vagy a bírálót, attól függően, hogy a felhasználói típus az-e
                    if($user->group_id == 2){
                        $internalConsultant = $this->Users->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $this->getRequest()->getData('internal_consultant_id')],
                                                                                              'contain' => ['Users']])->first();
                        if(empty($internalConsultant)) $user->setError('internal_consultant_id', __('A belső konzulens nem létezik.'));
                        elseif($internalConsultant->has('user') && $internalConsultant->user_id != $user->id) $user->setError('internal_consultant_id', __('A belső konzulenshez már tartozik felhasználói fiók.'));
                        else $user->internal_consultant = $internalConsultant;
                    }elseif($user->group_id == 7){
                        $reviewer = $this->Users->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $this->getRequest()->getData('reviewer_id')],
                                                                          'contain' => ['Users']])->first();
                        if(empty($reviewer)) $user->setError('reviewer_id', __('A bíráló nem létezik.'));
                        elseif($reviewer->has('user') && $reviewer->user_id != $user->id) $user->setError('reviewer_id', __('A bírálóhoz már tartozik felhasználói fiók.'));
                        else $user->reviewer = $reviewer;
                    }
                }
            }
            
            if($this->Users->save($user)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $user->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        $groups = $this->Users->Groups->find('list', ['conditions' => ['Groups.id IN' => [1, 2, 3, 4, 5, 7, 8]]]); //Hallgató kivételével mindenki
        $reviewers = $this->Users->Reviewers->find('list');
        $internalConsultants = $this->Users->InternalConsultants->find('list');
        $this->set(compact('user', 'groups', 'reviewers', 'internalConsultants'));
    }
    
    /**
     * Felhasználóo fiók részletei
     *
     * @param string|null $id Felhasználó egyedi azonosítója
     */
    public function details($id = null){
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $id, 'Users.group_id !=' => 6],
                                           'contain' => ['InternalConsultants', 'Reviewers', 'RawPasswords', 'Groups']])->first();
        if(empty($user)){
            $this->Flash->error(__('Felhasználói fiók adatai nem szerkeszthetőel.') . ' ' . __('A felhasználói fiók nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->set(compact('user'));
    }

    /**
     * Felhasználóo fiók törlése
     *
     * @param string|null $id Felhasználó egyedi azonosítója
     */
    public function delete($id = null){
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $id, 'Users.group_id !=' => 6]])->first();
        
        if(empty($user)){
            $this->Flash->error(__('Felhasználói fiók nem törölhető.') . ' ' . __('A felhasználói fiók nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->Users->delete($user)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
