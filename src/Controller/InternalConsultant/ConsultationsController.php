<?php
namespace App\Controller\InternalConsultant;

use App\Controller\AppController;

/**
 * Consultations Controller
 *
 * @property \App\Model\Table\ConsultationsTable $Consultations
 *
 * @method \App\Model\Entity\Consultation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConsultationsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') == 'finalize') $this->viewBuilder()->setLayout(false);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma konzultációi nem elérhetők.') . ' ' .  __('Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A téma konzultációi nem elérhetők.') . ' ' .  __('A témának nem Ön a belső konzulense.'));
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
            $this->Flash->error(__('A téma konzultációi nem elérhetők.') . __('A téma nincs elfogadva.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $consultations = $this->Consultations->find('all', ['conditions' => ['thesis_topic_id' => $thesisTopic->id], 'order' => ['current' => 'DESC', 'created' => 'DESC']]);
        
        $can_add_consultation_group = true;
        if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                           \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            foreach($consultations as $consultation){
                //A jelenlegi szakdolgozathoz már van egy konzultációs csoport és el van fogadva
                if($consultation->current === true && $consultation->accepted !== false){
                    $can_add_consultation_group = false;
                    break;
                }
            }
        }else $can_add_consultation_group = false;
        
        $this->set(compact('consultations', 'thesisTopic', 'can_add_consultation_group'));
    }
    
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A témához nem adhat hozzá konzultációs csoportot.') . ' ' .  __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A témához nem adhat hozzá konzultációs csoportot.') . ' ' .  __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){ //Nem "A téma nem elfogadott", nem "Első diplomakurzus teljesítve" státuszban van
            $this->Flash->error(__('A témához nem adhat hozzá konzultációs csoportot.') . ' ' .  __('A téma nincs abban az állapotban.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect($this->referer(null, true));
        
        $consultations = $this->Consultations->find('all', ['conditions' => ['thesis_topic_id' => $thesisTopic->id]]);
        $can_add_consultation_group = true;
        if(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                           \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            foreach($consultations as $consultation){
                //A jelenlegi szakdolgozathoz már van egy konzultációs csoport és el van fogadva
                if($consultation->current === true && $consultation->accepted !== false){
                    $can_add_consultation_group = false;
                    break;
                }
            }
        }else $can_add_consultation_group = false;
        
        if($can_add_consultation_group === false){
            $this->Flash->error(__('Nem adhat hozzá konzultációs csoportot. A jelenlegi dolgozat már rendelkezik eggyel.'));
            return $this->redirect(['action' => 'index', $thesisTopic->id]);
        }
        
        $consultation = $this->Consultations->newEntity();
        $consultation->thesis_topic_id = $thesisTopic->id;
        $consultation->current = true;
        if ($this->Consultations->save($consultation)){
            $this->Flash->success(__('Mentés sikeres.'));
            $consultations = $this->Consultations->find('all', ['conditions' => ['Consultations.thesis_topic_id' => $thesisTopic->id]]);
            //Az eddigi csoportok "régivé tétele"
            foreach($consultations as $consultation_){
                if($consultation_->id != $consultation->id){
                    $consultation_->current = false;
                    $this->Consultations->save($consultation_);
                }
            }
        }
        else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index', $thesisTopic->id]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Consultation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $consultation = $this->Consultations->find('all', ['conditions' => ['id' => $id]])->first();
        
        $ok = true;
        
        if(empty($consultation)){
            $this->Flash->error(__('Konzultációs csoport nem létezik.'));
            $ok = false;
        }elseif($consultation->accepted !== null){ //Már véglegesített
            $this->Flash->error(__('Konzultációs csoport már véglegesített, nem törölheti.'));
            $ok = false;
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $this->Flash->error(__('A konzultációs csoport régebbi dolgozathoz tartozik, nem törölheti.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect($this->referer(null, true));
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A konzultációs csoportot nem törölhető.') . ' ' .  __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A konzultációs csoportot nem törölhető.') . ' ' .  __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){ //Nem "A téma nem elfogadott", nem "Első diplomakurzus teljesítve" státuszban van
            $this->Flash->error(__('A konzultációs csoportot nem törölhető.') . ' ' .  __('A téma nincs abban az állapotban.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect($this->referer(null, true));
        
        if($this->Consultations->delete($consultation)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index', $consultation->thesis_topic_id]);
    }
    
    /**
     * Konzultációs csoport véglegesításe
     * 
     * @param type $id Konzultációs csoport azonosítója
     */
    public function finalize($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $consultation = $this->Consultations->find('all', ['conditions' => ['id' => $id]])->first();
        
        $error_msg = '';
        $ok = true;
        
        if(empty($consultation)){//Nem létezik a konzultációs csoport
            $ok = false;
            $error_msg = __('Konzultációs csoport nem létezik.');
        }elseif($consultation->accepted !== null){//Már véglegesítve van a konzultációs csoport
            $ok = false;
            $error_msg = __('A konzultációs csoport már véglegesítve van.');
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $ok = false;
            $error_msg = __('A konzultációs csoport régebbi dolgozathoz tartozik, nem véglegesíthető.');
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A konzultációs csoportot nem véglegesítheti.') . ' ' .  __('A téma, amelyhez tartozik nem létezik.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A konzultációs csoportot nem véglegesítheti.') . ' ' .  __('A témának, amelyhez tartozik, nem Ön a belső konzulense.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')){ //Nem "Az első diplimakurzus teljesítve" státuszban van
            $error_msg = __('A konzultációs csoportot nem véglegesítheti.') . ' ' .  __('A konzultációk csak akkor véglegesíthetőek, ha az első diplomakurzust teljesítve van.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $count_of_consultation_occasions = $this->Consultations->ConsultationOccasions->find('all', ['conditions' => ['consultation_id' => $consultation->id]])->count();
        
        if($count_of_consultation_occasions <= 0){
            $ok = false;
            $error_msg = __('A konzultációs csoporthoz nincs egy alkalom sem hozzárendelve. Így nem véglegesíthető.');
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $accepted = $this->getRequest()->getData('accepted');
            $consultation = $this->Consultations->patchEntity($consultation, ['accepted' => $accepted]);
            
            if($this->Consultations->save($consultation)){
                $this->Flash->success(__('Mentés sikeres!'));
                //Ha a "jelenlegi" szakdolgozathoz tartozik a konzultációs csoport és el lett fogadva
                if($consultation->current === true && $consultation->accepted === true){
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'); //A szakdolgozat/diplomamunka a formai követelményeknek megfelelt, feltölthető
                    if(!$this->Consultations->ThesisTopics->save($thesisTopic)){
                        $saved = false;
                        $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                        $consultation->accepted = null;
                        $this->Consultations->save($consultation);
                    }
                }
                //Ha nem felelt meg a követelményeknek, akkor erről egy értesítés
                if($consultation->current === true && $consultation->accepted === false){
                    $student = $this->Consultations->ThesisTopics->Students->find('all', ['conditions' => ['Students.id' => $thesisTopic->student_id],
                                                                                          'contain' => ['Users']])->first();
                    if(!empty($student) && $student->has('user')){
                        $this->loadModel('Notifications');

                        $notification = $this->Notifications->newEntity();
                        $notification->user_id = $student->user_id;
                        $notification->unread = true;
                        $notification->subject = 'A belső konzulense rögzítette, hogy a ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a formai követelményeknek nem felelt meg.';
                        $notification->message = 'A ' . h($thesisTopic->title) . ' című témához tartozó  ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a formai követelményeknek nem felelt meg, így ismét a második diplomakurzust kell teljesítenie.' .
                                                 '<br/><a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';

                        $this->Notifications->save($notification);
                    }
                }
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $consultation->getErrors();
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
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax', 'consultation'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
