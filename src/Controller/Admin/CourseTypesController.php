<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * CourseTypes Controller
 *
 * @property \App\Model\Table\CourseTypesTable $CourseTypes
 *
 * @method \App\Model\Entity\CourseType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CourseTypesController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Képzéstípus listája
     */
    public function index(){
        $courseTypes = $this->CourseTypes->find('all');
        $this->set(compact('courseTypes'));
    }
    
    /**
     * Képzéstípus hozzáadása
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
                
        $saved = true;
        $error_ajax = "";
        
        $courseType = $this->CourseTypes->newEntity();
        if($this->getRequest()->is('post')){
            $courseType = $this->CourseTypes->patchEntity($courseType, $this->getRequest()->getData());
            if($this->CourseTypes->save($courseType)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $courseType->getErrors();
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
        
        $this->set(compact('courseType', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Képzéstípus szerkesztése
     *
     * @param string|null $id Képzéstípus egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $courseType = $this->CourseTypes->find('all', ['conditions' => ['id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($courseType)){ //Ha nem létezik a képzéstípus
            $error_msg = __('A kért képzéstípus nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $courseType = $this->CourseTypes->patchEntity($courseType, $this->getRequest()->getData());
            if($this->CourseTypes->save($courseType)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $courseType->getErrors();
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
        
        $this->set(compact('courseType', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Képzéstípus törlése
     *
     * @param string|null $id Képzéstípus egyedi aznosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $courseType = $this->CourseTypes->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($courseType)){
            $this->Flash->error(__('Képzéstípus nem törölhető.') . ' ' . __('A képzéstípus nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $query = $this->CourseTypes->Students->find();
        //Olyan hallgatók akik ezen a képzéstípuson vannak és még van folyamatban lévő témájuk
        $count_of_students = $query->where(['Students.course_type_id' => $courseType->id])
                                   ->matching('ThesisTopics', function ($q){ return $q->where(['ThesisTopics.thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]]);})
                                   ->count();
                            
        if($count_of_students > 0){
            $this->Flash->error(__('Képzéstípus nem törölhető.') . ' ' . __('Még van olyan hallgató, aki ezen a képzéstípuson van és még van folyamatban lévő témaja.'));
            return $this->redirect(['action' => 'index']);
        }        
        
        if($this->CourseTypes->delete($courseType)) $this->Flash->success(__('Törlés sikeres'));
        else $this->Flash->error(__('Törlés sikertelen. Kérjük próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
