<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 *
 * @method \App\Model\Entity\Department[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepartmentsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Tanszékek listája
     */
    public function index(){
        $departments = $this->Departments->find('all');
        $this->set(compact('departments'));
    }
    
    /**
     * Tanszék hozzáadása
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
                
        $saved = true;
        $error_ajax = "";
        
        $department = $this->Departments->newEntity();
        if($this->getRequest()->is('post')){
            $department = $this->Departments->patchEntity($department, $this->getRequest()->getData());
            if($this->Departments->save($department)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $department->getErrors();
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
        
        $this->set(compact('department', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Tanszék szerkesztése
     *
     * @param string|null $id Tanszék egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $department = $this->Departments->find('all', ['conditions' => ['id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($department)){ //Ha nem létezik a tanszék
            $error_msg = __('A kért tanszék nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $department = $this->Departments->patchEntity($department, $this->getRequest()->getData());
            if($this->Departments->save($department)) {
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $department->getErrors();
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
        
        $this->set(compact('department', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Tanszék törlése
     *
     * @param string|null $id Tanszék egyedi aznosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $department = $this->Departments->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($department)){
            $this->Flash->error(__('Tanszék nem törölhető.') . ' ' . __('A tanszék nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $query = $this->Departments->InternalConsultants->find();
        //Olyan belső konzulensek a tanszékről, akiknek még van folyamatban lévő témájuk
        $count_of_internalConsultants = $query->where(['InternalConsultants.department_id' => $department->id])
                                     ->matching('ThesisTopics', function ($q){ return $q->where(['ThesisTopics.thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                                                                                                  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]]);})
                                     ->count();
                            
        if($count_of_internalConsultants > 0){
            $this->Flash->error(__('Tanszék nem törölhető.') . ' ' . __('A tanszékhez még tartozik folyamatban lévő téma.'));
            return $this->redirect(['action' => 'index']);
        }        
        
        if($this->Departments->delete($department)) $this->Flash->success(__('Törlés sikeres'));
        else $this->Flash->error(__('Törlés sikertelen. Kérjük próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
