<?php
namespace App\Controller\ThesisManager;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'),['acceptThesisSupplements', 'applyAcceptedThesisData'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Szakdolgozatkezelő témalista(szakdolgozatlista)
     */
    public function index(){
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]],
                                                          'contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

        $this->set(compact('thesisTopics'));
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages', 'ThesisSupplements',
                                                                       'Reviews' => ['Reviewers']]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ //A szakdolgozati feltöltés nincs véglegesítve
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A dolgozat még nincs abban az állapotban, hogy elérheti.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Mellékletek elfogadása/elutasítása
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function acceptThesisSupplements($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $error_msg = __('A mellékletek nem bírálhatóak.') . ' ' . __('A dolgozat nem létezik.');
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements')){ //A szakdolgozati feltöltés nincs véglegesítve
            $ok = false;
            $error_msg = __('A mellékletek nem bírálhatóak.') . ' ' . __('A dolgozat felöltése még nincs véglegesítve.');
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $accepted = $this->getRequest()->getData('accepted');
            
            if($accepted === null || !in_array($accepted, [0, 1])){
                $thesisTopic->review->setError('custom', __('A döntésnek "0" (nem) vagy "1" (igen) értéket kell felvennie!'));
            }else{
                if($accepted == 0){
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'); //Elutasítva
                    $thesisTopic->cause_of_rejecting_thesis_supplements = $this->getRequest()->getData('cause_of_rejecting_thesis_supplements');
                }else $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'); //Elfogadva
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!');
                
                $errors = $thesisTopic->getErrors();
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
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Elfogadott dolgozat adatainak felvitelének rögzítése a Neptun rendszerbe
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function applyAcceptedThesisData($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true],
                                                         'contain' => ['InternalConsultants', 'Reviews' => ['Reviewers']]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $error_msg = __('Az adatok felvitele nem rögzíthető.') . ' ' . __('Nem létezik a dolgozat.');
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){ //A dolgozat még nincs elfogadva
            $ok = false;
             $error_msg = __('Az adatok felvitele nem rögzíthető.') . ' ' . __('A dolgozat még nincs elfogadott állapotban.');
        }elseif($thesisTopic->accepted_thesis_data_applyed_to_neptun === true){ //Az adatok már fel vannak vive
            $ok = false;
            $error_msg = __('Az adatok felvitele nem rögzíthető.') . ' ' . __('A dolgozat adatainak felvitele már megtörtént.');
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $thesisTopic->accepted_thesis_data_applyed_to_neptun = true;
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Az adatok felvitele rögzítve.'));
            }else{
                $saved = false;
                $error_ajax = __('Az adatok felvitelét nem sikerült rögzíteni. Próbálja újra!');
                
                $errors = $thesisTopic->getErrors();
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
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
