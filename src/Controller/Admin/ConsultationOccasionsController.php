<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * ConsultationOccasions Controller
 *
 * @property \App\Model\Table\ConsultationOccasionsTable $ConsultationOccasions
 *
 * @method \App\Model\Entity\ConsultationOccasion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConsultationOccasionsController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($consultation_id = null){
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        if(empty($consultation)){
            $this->Flash->error(__('Konzultációs csoport nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A konzultációs csoportotokat nem láthatja.') . ' ' . __('A téma, amelyhez tartozik nem létezik.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')])){
            $this->Flash->error(__('A konzultációs csoportotokat nem láthatja.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy eléreje az alkalmakat.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $consultationOccasions = $this->ConsultationOccasions->find('all', ['conditions' => ['consultation_id' => $consultation->id], 'order' => ['date' => 'DESC']]);
        $this->set(compact('consultationOccasions', 'consultation', 'thesisTopic'));
    }
    
    /**
     * Konzultációs alkalom hozzáadása
     */
    public function add($consultation_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.');
            $ok = false;
        }elseif($consultation->accepted !== null){ //Már véglegesített
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik már véglegesített.');
            $ok = false;
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik régebbi dolgozathoz tartozik.');
            $ok = false;
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A téma, amelyhez tartozik nem létezik.');
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')])){
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy hozzáadhatna konzultácós alkalmat.');
            $ok = false;
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        
        $consultationOccasion = $this->ConsultationOccasions->newEntity();
        $consultationOccasion->consultation_id = $consultation->id;
        if($this->getRequest()->is('post')){
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->getRequest()->getData());
            if($this->ConsultationOccasions->save($consultationOccasion)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $consultationOccasion->getErrors();
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
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Konzultációs alkalom szerkesztése
     *
     * @param string|null $id Konzultációs alkalom egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $consultationOccasion = $this->ConsultationOccasions->find('all', ['conditions' => ['id' => $id]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($consultationOccasion)){ //Ha nem létezik a konzultációs alkalom
            $error_msg = __('A kért konzultációs alkalom nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultationOccasion->consultation_id]])->first();
        
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }

        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A téma, amelyhez tartozik nem létezik.');
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')])){
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az éllapotban, hogy szerkeszthető lenne a konzultációs alkalom.');
            $ok = false;
        }

        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->getRequest()->getData());
            if($this->ConsultationOccasions->save($consultationOccasion)) {
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $consultationOccasion->getErrors();
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
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Konzultációs alkalom törlése
     *
     * @param string|null $id Konzultációs alkalom egyedi aznosítója
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        
        $consultationOccasion = $this->ConsultationOccasions->find('all', ['conditions' => ['id' => $id]])->first();
        if(empty($consultationOccasion)){ //Ha nem létezik a konzultációs alkalom
            $this->Flash->error(__('A kért konzultációs alkalom nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultationOccasion->consultation_id]])->first();
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A téma, amelyhez tartozik nem létezik.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')])){
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy törölhető lenne a konzultációs alkalom.'));
            $ok = false;
        }

        if(!$ok) return $this->redirect($this->referer(null, true));
        
        if($this->ConsultationOccasions->delete($consultationOccasion)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index', $consultationOccasion->consultation_id]);
    }
}
