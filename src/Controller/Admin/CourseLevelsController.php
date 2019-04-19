<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * CourseLevels Controller
 *
 * @property \App\Model\Table\CourseLevelsTable $CourseLevels
 *
 * @method \App\Model\Entity\CourseLevel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CourseLevelsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Képzésszintek listája
     */
    public function index(){
        $courseLevels = $this->CourseLevels->find('all');
        $this->set(compact('courseLevels'));
    }
    
    /**
     * Képzésszint hozzáadása
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
                
        $saved = true;
        $error_ajax = "";
        
        $courseLevel = $this->CourseLevels->newEntity();
        if($this->getRequest()->is('post')){
            $courseLevel = $this->CourseLevels->patchEntity($courseLevel, $this->getRequest()->getData());
            if($this->CourseLevels->save($courseLevel)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $courseLevel->getErrors();
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
        
        $this->set(compact('courseLevel', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Képzésszint szerkesztése
     *
     * @param string|null $id Képzésszint egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $courseLevel = $this->CourseLevels->find('all', ['conditions' => ['id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($courseLevel)){ //Ha nem létezik a képzésszint
            $error_msg = __('A kért képzésszint nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $courseLevel = $this->CourseLevels->patchEntity($courseLevel, $this->getRequest()->getData());
            if($this->CourseLevels->save($courseLevel)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $courseLevel->getErrors();
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
        
        $this->set(compact('courseLevel', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Képzésszint törlése
     *
     * @param string|null $id Képzésszint egyedi aznosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $courseLevel = $this->CourseLevels->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($courseLevel)){
            $this->Flash->error(__('Képzésszint nem törölhető.') . ' ' . __('A képzésszint nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $query = $this->CourseLevels->Students->find();
        //Olyan hallgatók akik ezen a képzésszinten vannak és még van folyamatban lévő témájuk
        $count_of_students = $query->where(['Students.course_level_id' => $courseLevel->id])
                                   ->matching('ThesisTopics', function ($q){ return $q->where(['ThesisTopics.thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                                                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]]);})
                                   ->count();
                            
        if($count_of_students > 0){
            $this->Flash->error(__('Képzésszint nem törölhető.') . ' ' . __('Még van olyan hallgató, aki ezen a képzésszinten van és még van folyamatban lévő témaja.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->CourseLevels->delete($courseLevel)) $this->Flash->success(__('Törlés sikeres'));
        else $this->Flash->error(__('Törlés sikertelen. Kérjük próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
