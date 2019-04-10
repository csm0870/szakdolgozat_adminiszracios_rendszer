<?php
namespace App\Controller\HeadOfDepartment;

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
        if(in_array($this->getRequest()->getParam('action'), ['decideToContinueAfterFailedFirstThesisSubject', 'proposalForAmendment'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Témalista
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak azokat a témákat látja, amelyet a belső konzulens már elfogadott
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant')] /* Már eljutott a tanszékvezetőig */],
                                                          'contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses', 'Reviews'], 'order' => ['ThesisTopics.modified' => 'DESC']]);
        $this->loadModel('Information');
        $information = $this->Information->find('all')->first();
        $this->set(compact('thesisTopics', 'information'));
    }
    
    /**
     * Téma elfogadása vagy elutasítása
     * @return type
     */
    public function accept(){
        if($this->getRequest()->is('post')){
            $thesisTopic_id = $this->getRequest()->getData('thesis_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesisTopic_id, 'ThesisTopics.deleted !=' => true]])->first();
            
            $ok = true;
            if(empty($thesisTopic)){
                $this->Flash->error(__('Erről a témáról nem dönthet.') . ' ' . __('A téma nem létezik.'));
                $ok = false;
            }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ //Nem "A téma tanszékvezetői döntésre vár" státuszban van
                $this->Flash->error(__('Erről a témáról nem dönthet.') . ' ' . __('Nem tanszékvezetői döntésre vár.'));
                $ok = false;
            }
            
            if(!$ok) return $this->redirect(['action' => 'index']);
            
            //Elutasítás vagy elfogadás esetén, ha van külső konzulens, akkor külső konzulensi ellenőrzésre vár státuszú lesz, ha nincs, akkor pedig elfogadva
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment') : ($thesisTopic->cause_of_no_external_consultant === null ? \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic') :  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'));

            if($this->ThesisTopics->save($thesisTopic))  $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            else $this->Flash->error(($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!'));
            
            return $this->redirect(['action' => 'details', $thesisTopic->id]);
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id Téma azonosítója
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages', 'ThesisSupplements',
                                                                       'Reviews' => ['Reviewers' => ['Users' => ['RawPasswords']]]]])->first();
    
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant')])){ //Tanszékvezető döntése alatti státuszokban van
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma nincs abban az állapotban.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect (['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Téma módosítási javaslat
     * 
     * @param type $id Téma azonosítója
     */
    public function proposalForAmendment($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Nem adhat módosítási javaslatot.') . ' ' . __('Nem létező téma.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){ //Nem "A téma a tanszékvezető döntésére vár" státuszban van
            $error_msg = __('Nem adhat módosítási javaslatot.') . ' ' . __('A téma nem a tanszékvezető döntésére vár.');
            $ok = false;
        }
        
        //Ha a feltételeknek nem megfelelő téma
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $proposal_for_amendment = $this->getRequest()->getData('proposal_for_amendment');
            
            if(empty($proposal_for_amendment)){
                $thesisTopic->setError('custom', __('A javaslatot kötelező kitölteni.'));
            }else{ //Ajánlott témánál pedig a belső konzulenshez kerüljön vissza sztem
                $thesisTopic->proposal_for_amendment = $proposal_for_amendment;
                $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment');
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Kérjük próbálja újra!');
                
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
     * Első diplimakurzus sikertelensége eseténi döntés, hogy folytathatja-e a hallgató a témát vagy újat válasszon
     * 
     * @param type $id Téma azonosítója
     */
    public function decideToContinueAfterFailedFirstThesisSubject($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Nem dönthet.') . ' ' . __('Nem létező téma.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){ //Nem "Első diplomakurzus sikertelen, tanszékvezető döntésére vár" státuszban van
            $error_msg = __('Nem dönthet.') . ' ' . __('A téma nem "Első diplomakurzus sikertelen" állapotban van.');
            $ok = false;
        }
        
        //Ha a feltételeknek nem megfelelő téma
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $decide_to_continue = $this->getRequest()->getData('decide_to_continue');
            
            if($decide_to_continue === null || !in_array($decide_to_continue, [0, 1])){
                $thesisTopic->setError('custom', __('A döntésnek "0"(nem) vagy "1"(igen) értéket kell felvennie!'));
            }else{
                if($decide_to_continue == 0){ //Új témát kell választania
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'); //Téma elutasítva (első diplomakurzus sikertelen)
                }else{ //Javíthatja a diplomakurzust a jelenlegi témával
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'); //Elfogadva
                }
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success($decide_to_continue == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($decide_to_continue == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!');
                
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
