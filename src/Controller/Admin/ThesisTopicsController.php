<?php
namespace App\Controller\Admin;

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
    
    public function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['decideToContinueAfterFailedFirstThesisSubject', 'proposalForAmendment',
                                                              'setFirstThesisSubjectCompleted', 'acceptThesisSupplements', 'setThesisGrade',
                                                              'applyAcceptedThesisData']))
            $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Témalista
     */
    public function index(){
        $thesisTopics = $this->ThesisTopics->find('all', ['contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);
        $this->loadModel('Information');
        $information = $this->Information->find('all')->first();
        $this->set(compact('thesisTopics', 'information'));
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id Téma azonosítója
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'], 'OfferedTopics',
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages', 'ThesisSupplements',
                                                                       'Reviews' => ['Reviewers' => ['Users' => ['RawPasswords']]]]])->first();
    
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            return $this->redirect (['action' => 'index']);
        }
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Téma szerkesztése
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function edit($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages']])->first();
    
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            return $this->redirect (['action' => 'index']);
        }
        
        if($this->request->is(['patch', 'post', 'put'])){
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->getRequest()->getData());
            //Hallgató és belső konzulens nem módosítható
            unset($thesisTopic->stundet_id);
            unset($thesisTopic->internal_consultant_id);
            $has_external_consultant = $this->getRequest()->getData('has_external_consultant');
            
            //Külső konzulensi mezők beállítása
            if(empty($has_external_consultant) || $has_external_consultant != 1){
                $thesisTopic->external_consultant_name = null;
                $thesisTopic->external_consultant_position = null;
                $thesisTopic->external_consultant_workplace = null;
                $thesisTopic->external_consultant_email = null;
                $thesisTopic->external_consultant_phone_number = null;
                $thesisTopic->external_consultant_address = null;
            }else{
                $thesisTopic->cause_of_no_external_consultant = null;
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $thesisTopic->id]);
            }
            $this->Flash->error(__('Hiba történt. Próbálja újra!'));
        }
        
        $this->loadModel('Years');
        $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
        $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
        $languages = $this->ThesisTopics->Languages->find('list');
        $this->set(compact('thesisTopic', 'internalConsultants', 'years', 'languages'));
    }
    
    /**
     * Téma véglegesítés (hallgatói művelet)
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalizeThesisTopic($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){ //Nem véglegesítésre vár
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem véglegesíthető állapotban van.'));
            $ok = false;
        }
        
        if($ok === true){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.id' => $thesisTopic->student_id],
                                                                   'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $data = $this->Students->checkStundentData($student->user->id);
                if($data['success'] === false){
                    $this->Flash->error(__('A hallgatónak nincs megadva minden szükskéges adat a téma leadásához.') . ' ' . __('A téma nem véglegesíthető állapotban van.'));
                    $ok = false;
                }
            }else{
                $this->Flash->error(__('A hallgató nem létezik vagy nincs hozzárendelve felhasználó.') . ' ' . __('A téma nem véglegesíthető állapotban van.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        //Belső konzulensi döntésre vár
        $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic');
        
        //Mezők ellenőrzése, mentés előtt
        if(empty($thesisTopic->title)) $thesisTopic->setError('title', __('Cím megadása kötelező.'));
        if(empty($thesisTopic->description)) $thesisTopic->setError('description', __('Leírás megadása kötelező.'));
        if($thesisTopic->is_thesis === null) $thesisTopic->setError('is_thesis', __('Dolgozat típusának megadása kötelező.'));
        if($thesisTopic->confidential === null) $thesisTopic->setError('confidential', __('Titkosítottság megadása kötelező.'));
        if($thesisTopic->starting_semester === null) $thesisTopic->setError('starting_semester', __('Kezdési félév megadása kötelező.'));
        if($thesisTopic->expected_ending_semester === null) $thesisTopic->setError('expected_ending_semester', __('Kezdési tanév megadása kötelező.'));
        if($thesisTopic->cause_of_no_external_consultant === null){ //Ha van külső konzulens
            //Külső konzulens adatainak ellenőrzése: nem lehetnek üresek
            if(empty($thesisTopic->external_consultant_name)) $thesisTopic->setError('external_consultant_name', __('Külső konzulens nevének megadása kötelező.'));
            if(empty($thesisTopic->external_consultant_workplace)) $thesisTopic->setError('external_consultant_workplace', __('Külső konzulens munkahelyének megadása kötelező.'));
            if(empty($thesisTopic->external_consultant_position)) $thesisTopic->setError('external_consultant_position', __('Külső konzulens poziciójának megadása kötelező.'));
            if(empty($thesisTopic->external_consultant_email)) $thesisTopic->setError('external_consultant_email', __('Külső konzulens e-mail címének megadása kötelező.'));
            elseif(\Cake\Validation\Validation::email($thesisTopic->external_consultant_email) === false) $thesisTopic->setError('external_consultant_email', __('Külső konzulens e-mail cím nem megfelelő formátumú.'));
            if(empty($thesisTopic->external_consultant_phone_number)) $thesisTopic->setError('external_consultant_phone_number', __('Külső konzulens telefonszámának megadása kötelező.'));
            if(empty($thesisTopic->external_consultant_address)) $entity->setError('external_consultant_address', __('Külső konzulens címének megadása kötelező.'));
        }elseif(empty($thesisTopic->cause_of_no_external_consultant)){
            //Ha nincs külső konzulens, akkor annak indoklása kötelező
            $thesisTopic->setError('cause_of_no_external_consultant', __('Külső konzulenstől való eltekintés indoklása kötelező.'));
        }
        
        if(empty($thesisTopic->internal_consultant_id)) $thesisTopic->setError('internal_consultant_id', __('Belső konzulens megadása kötelező.'));
        if(empty($thesisTopic->language_id)) $thesisTopic->setError('language_id', __('Nyelv megadása kötelező.'));
        if(empty($thesisTopic->student_id)) $thesisTopic->setError('student_id', __('Hallgató megadása kötelező.'));
        if(empty($thesisTopic->starting_year_id)) $thesisTopic->setError('starting_year_id', __('Kezdési tanév megadása kötelező.'));
        if(empty($thesisTopic->expected_ending_year_id)) $thesisTopic->setError('expected_ending_year_id', __('Várható leadási tanév megadása kötelező.'));
        
        if($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
        else{
            $error_msg = __('Hiba történt. Próbálja újra!');
            
            $errors = $thesisTopic->getErrors();
            if(!empty($errors)){
                foreach($errors as $error){
                    if(is_array($error)){
                        foreach($error as $err){
                            $error_msg.= ' ' . $err;
                        }
                    }else{
                        $error_msg.= ' ' . $error;
                    }
                }
            }
            
            $this->Flash->error($error_msg);
        }

        return $this->redirect(['action' => 'details', $thesisTopic->id]);
    }
    
    /**
     * Foglalás elfogadása/elutasítása (belső konzulensi művelet)
     * 
     * @return type
     */
    public function acceptBooking(){
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $thesis_topic_id = $this->getRequest()->getData('thesis_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }
            
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                             'contain' => ['OfferedTopics']])->first();

            $ok = true;
            
            if(empty($thesisTopic)){ //Nem létezik a téma
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem létező téma.'));
            }elseif(!$thesisTopic->has('offered_topic')){ //Nem foglalt téma
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('A téma nem foglalt téma.'));
            }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){ ////Nem foglalás elfogadására vár
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem foglalás elfogadására vár.'));
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
            
            if($accepted == 0){
                $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant');
                $thesisTopic->offered_topic_id = null;
            }else{
                $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking');
            }

            if($this->ThesisTopics->save($thesisTopic)) $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            else $this->Flash->error(($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!'));
			
			return $this->redirect(['action' => 'details', $thesisTopic->id]);
		}
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Foglalás visszavonása (hallgatói művelet)
     * 
     * @param type $id
     */
    public function cancelBooking($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A foglalás nem vonható vissza.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->offered_topic_id === null){ //Nincs foglalás a témához
            $this->Flash->error(__('A foglalás nem vonható vissza.') . ' ' . __('A téma nem témafoglalás.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){ //A témafoglalás nem a hallgató véglegesítésére vár
            $this->Flash->error(__('A foglalás nem vonható vissza.') . ' ' . __('A foglalás nem a hallgató véglegesítésére vár.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent');
        $thesisTopic->offered_topic_id = null;

        if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Visszavonás sikeres.'));
        else $this->Flash->error(__('Hiba történt. Próbálja újra!'));
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma elfogadása vagy elutasítása (belső konzulensi/tanszékvezetői/témakezelői művelet)
     * @return type
     */
    public function acceptThesisTopic(){
        if($this->getRequest()->is('post')){
            $thesisTopic_id = $this->getRequest()->getData('thesis_topic_id');
            $user_type = $this->getRequest()->getData('user_type');
            $accepted = $this->getRequest()->getData('accepted');

            $ok = true;
            if(empty($user_type) || !in_array($user_type, [1, 2, 3])){ //1 - belső konzulens, 2 - tanszékvezető, 3 - témakezelő
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                $ok = false;
            }elseif(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                $ok = false;
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
           
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesisTopic_id]])->first();

            //Állapot és a hozzá tartozó üzenet definiálása
            if($user_type == 1){
                $status_msg = __('A téma nem a belső konzulens elfogadására vár.');
                $status = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic');
            }elseif($user_type == 2){
                $status_msg = __('A téma nem a tanszékvezető elfogadására vár.');
                $status = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic');
            }else{
                $status_msg = __('A téma nem a külső konzulensi aláírás ellenőrzésére vár.');
                $status = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic');
            }
            
            $ok = true;
            if(empty($thesisTopic)){ //Nem létezik a téma
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem létező téma.'));
            }elseif($thesisTopic->thesis_topic_status_id != $status){ //Nem "A téma belső konzulensi döntésre vár" státuszban van
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . $status_msg);
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
            
            if($user_type == 1) $thesisTopic->thesis_topic_status_id = $accepted == 0 ? \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant') : \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic');
            elseif($user_type == 2) $thesisTopic->thesis_topic_status_id = $accepted == 0 ? \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment') : ($thesisTopic->cause_of_no_external_consultant === null ? \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic') :  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'));
            else $thesisTopic->thesis_topic_status_id =  $accepted == 0 ? \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant') : \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted');
                
            if($this->ThesisTopics->save($thesisTopic)) $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            else $this->Flash->error(($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikeretlen.')) . ' ' . __('Próbálja újra!'));

            return $this->redirect(['action' => 'details', $thesisTopic->id]);
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma módosítási javaslat (tanszékvezetői művelet)
     * 
     * @param type $id Téma azonosítója
     */
    public function proposalForAmendment($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();
        
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
     * Diplomakurzus első félévének teljesítésének rögzítése (belső konzulensi művelet)
     * 
     * @param type $id Téma azonosítója
     */
    public function setFirstThesisSubjectCompleted($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('Nem létező téma.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){ //Nem "A téma elfogadott" státuszban van
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('A téma nem elfogadott státuszban van.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $first_thesis_subject_completed = $this->getRequest()->getData('first_thesis_subject_completed');
            
            if($first_thesis_subject_completed === null || !in_array($first_thesis_subject_completed, [0, 1])){
                $thesisTopic->setError('custom', __('A döntésnek "0"(nem) vagy "1"(igen) értéket kell felvennie!'));
            }else{
                if($first_thesis_subject_completed == 0){ //Első diplomakurzus sikertelen
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision'); //Téma elutasítva (első diplomakurzus sikertelen)
                    
                    $suggestion = $this->getRequest()->getData('first_thesis_subject_failed_suggestion');
                    if(empty($suggestion)) $thesisTopic->setError('first_thesis_subject_failed_suggestion', __('Az javaslatot kötelező megadni.'));
                    else $thesisTopic->first_thesis_subject_failed_suggestion = $suggestion;
                }else{ //Első diplomakurzus sikeres
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded'); //Első diplomakurzus teljesítve
                }
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
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
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();
        
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
    
    /**
     * Szakdolgozat/diplomamunka, mellékletek feltöltése. (hallgatói művelet, de az admin bármikor módosíthatja)
     * 
     * @param type $thesis_topic_id
     */
    public function uploadThesisSupplements($thesis_topic_id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                         'contain' => ['ThesisSupplements', 'ThesisTopicStatuses']])->first(); 
    
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Dolgozat nem tölthető fel.') . ' ' . __('Nem létezik a dolgozat.'));
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
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){
            $this->Flash->error(__('Dolgozat nem tölthető fel.') . ' ' . __('A dolgozat nincs abban az állapotban.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        if($this->getRequest()->is(['post', 'put', 'patch'])){
            $thesis_supplements = $this->getRequest()->getData('thesis_supplements');
            
            $ok = true;
            foreach($thesis_supplements as $supplement){
                $thesisSupplement = $this->ThesisTopics->ThesisSupplements->newEntity();
                if(!empty($supplement['name'])){
                    $supplement['name'] = $this->addFileName($supplement['name'], ROOT . DS . 'files' . DS . 'thesis_supplements');
                    $thesisSupplement->file = $supplement;
                    $thesisSupplement->thesis_topic_id = $thesisTopic->id;
                    if(!$this->ThesisTopics->ThesisSupplements->save($thesisSupplement)){
                        $this->Flash->error(__('Melléklet mentése sikertelen. Próbálja újra!'));
                        $ok = false;
                        break;
                    }
                }
            }
            
            if($ok){
                //Csak akkor változik a téma állapota, ha mellékletek feltöltésére várnak vagy el vannak utasítva, ha nem akkor csak simán mentve vannak
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable') ||
                   $thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected')){
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement');
                    if($this->ThesisTopics->save($thesisTopic)){
                        $this->Flash->success(__('Mentés sikeres.'));
                        return $this->redirect(['action' => 'uploadThesisSupplements', $thesisTopic->id]);
                    }
                    $this->Flash->error(__('Mentés sikertelen. Próbálja újra!!'));
                }else{
                    $this->Flash->success(__('Mentés sikeres.'));
                    return $this->redirect(['action' => 'uploadThesisSupplements', $thesisTopic->id]);
                }
                    
            }
        }
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Dolgozat melléklet feltöltésének véglegesítés (hallgatói művelet)
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalizeUploadedThesisSupplements($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id, 'deleted !=' => true], 'contain' => ['ThesisSupplements']])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement')){ //Nem "Szakdolgozat feltöltve, hallgató véglegesítésére vár" státuszban van
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Még nem lett feltöltve.'));
            $ok = false;
        }elseif(count($thesisTopic->thesis_supplements) == 0){
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Nincs feltöltve melléklet.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        //Mellékletek ellenőrzésére vár
        $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements');
        if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
        else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

        return $this->redirect(['action' => 'uploadThesisSupplements', $thesisTopic->id]);
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
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();
        
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
                    
                    $cause = $this->getRequest()->getData('cause_of_rejecting_thesis_supplements');
                    if(empty($cause)) $thesisTopic->setError('cause_of_rejecting_thesis_supplements', __('Az okot kötelező megadni.'));
                    else $thesisTopic->cause_of_rejecting_thesis_supplements = $cause;
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
     * Dolgozat értékelése (belső konzulensi művelet)
     * 
     * @param type $id Téma azonosítója
     */
    public function setThesisGrade($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('Nem létező dolgozat.');
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
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ //Nincs legalább "Formai követelményeknek megfelelt" státuszban
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('A dolgozat még nincs abban az állapotban, hogy értékelhető legyen.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            
            $internal_consultant_grade = $this->getRequest()->getData('internal_consultant_grade');
            if(isset($internal_consultant_grade)){
                $thesisTopic->internal_consultant_grade = $internal_consultant_grade;
                if($this->ThesisTopics->save($thesisTopic)){
                    $this->Flash->success(__('Mentés sikeres!'));
                }else{
                    $saved = false;
                    $error_ajax = __('Mentés sikertelen. Próbálja újra!');

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
            }else{
                $saved = false;
                $error_ajax = __('Értékelés megadása kötelező.');
            }
        }
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Elfogadott dolgozat adatainak felvitelének rögzítése a Neptun rendszerbe vagy pedig, ha már rögzítve vannak, akkor annak jelzése,
     * hogy még sem viték őket fel
     * 
     * @param type $id Téma azonosítója
     * @return type
     */
    public function applyAcceptedThesisData($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['InternalConsultants', 'Reviews' => ['Reviewers']]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $error_msg = __('Az adatok felvitele nem rögzíthető.') . ' ' . __('Nem létezik a dolgozat.');
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){ //A dolgozat még nincs elfogadva
            $ok = false;
            $error_msg =  __('Az adatok felvitele nem rögzíthető.') . ' ' . __('A dolgozat még nincs elfogadott állapotban.');
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            if($thesisTopic->accepted_thesis_data_applyed_to_neptun !== true)
                $thesisTopic->accepted_thesis_data_applyed_to_neptun = true;
            else
                $thesisTopic->accepted_thesis_data_applyed_to_neptun = false;
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
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
     * Statisztika diagramok (képzés, képzés típus, képzés szint) (csak elfogadott témák)
     * 
     * @param type $year_id Tanév azonosítója
     * @param type $semester Félév
     */
    public function statistics($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => date('Y')]])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
			return $this->redirect(['action' => 'index']);
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        //Címkék
        $this->loadModel('Courses');
        $labels_for_courses_ = $this->Courses->find('list');
        $this->loadModel('CourseTypes');
        $labels_for_course_types_ = $this->CourseTypes->find('list');
        $this->loadModel('CourseLevels');
        $labels_for_course_levels_ = $this->CourseLevels->find('list');
        
        //Címkék sima tömbbe a diagramhoz
        $labels_for_courses = [];
        $labels_for_course_types = [];
        $labels_for_course_levels = [];
        
        //Diagramm adatok
        $data_for_courses = [];
        $data_for_course_types = [];
        $data_for_course_levels = [];
        
        //Képzésekhez tartozó témák számlálása
        foreach($labels_for_courses_ as $course_id => $course){
            $query = $this->ThesisTopics->find();
            $data_for_courses[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                 'ThesisTopics.starting_semester' => $semester,
                                                 'ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'), /* Elfogadott téma */
                                                 'ThesisTopics.deleted !=' => true])
                                        ->matching('Students', function ($q) use($course_id) { return $q->where(['Students.course_id' => $course_id]);})
                                        ->count();
            $labels_for_courses[] = $course;
        }
        
        //Képzési típusokhoz tartozó témák számlálása
        foreach($labels_for_course_types_ as $course_type_id => $course_type){
            $query = $this->ThesisTopics->find();
            $data_for_course_types[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                      'ThesisTopics.starting_semester' => $semester,
                                                      'ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'), /* Elfogadott téma */
                                                      'ThesisTopics.deleted !=' => true])
                                             ->matching('Students', function ($q) use($course_type_id) { return $q->where(['Students.course_type_id' => $course_type_id]);})
                                             ->count();
                                             
            $labels_for_course_types[] = $course_type;              
        }
        
        //Képzési szintekhez tartozó témák számlálása
        foreach($labels_for_course_levels_ as $course_level_id => $course_level){
            $query = $this->ThesisTopics->find();
            $data_for_course_levels[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                       'ThesisTopics.starting_semester' => $semester,
                                                       'ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'), /* Elfogadott téma */
                                                       'ThesisTopics.deleted !=' => true])
                                               ->matching('Students', function ($q) use($course_level_id) { return $q->where(['Students.course_level_id' => $course_level_id]);})
                                               ->count();
            
            $labels_for_course_levels[] = $course_level;
        }
        
        $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
        $this->set(compact('labels_for_courses', 'labels_for_course_types', 'labels_for_course_levels',
                           'data_for_courses', 'data_for_course_types', 'data_for_course_levels', 'years', 'year', 'semester'));
        
    }
    
    /**
     * Téma adatok exportálása (csak elfogadott témák)
     * 
     * @param type $year_id Tanév azonosítója
     * @param type $semester Félév
     * @return type
     */
    public function exports($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad  vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => '%' . date('Y') . '%']])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
            return $this->redirect(['action' => 'index']);
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
        
        $this->set(compact('year', 'semester', 'years'));
    }
    
    /**
     * CSV témalista adott év adott félévére
     * 
     * @param type $year_id Tanév azonosítója
     * @param type $semester Félév
     */
    public function exportCsv($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad  vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => '%' . date('Y') . '%']])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
            return $this->redirect(['action' => 'exports']);
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        $headers = ['Neptun kód', 'Név', 'Belső konzulens', 'Téma címe', 'Szak', 'Képzés típusa', 'Képzés szintje', 'Tanév', 'Félév'];
        
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.starting_year_id' => $year->id, 'ThesisTopics.deleted !=' => true, 'ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')], //Elfogadott témák
                                                          'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                        'StartingYears', 'InternalConsultants']]);
        $data = [];
        foreach($thesisTopics as $thesisTopic){
            if($thesisTopic->has('student')){
                $data[] = [$thesisTopic->student->neptun, $thesisTopic->student->name,
                           $thesisTopic->has('internal_consultant') ? $thesisTopic->internal_consultant->name : '-',
                           $thesisTopic->title, $thesisTopic->student->has('course') ? $thesisTopic->student->course->name : '-',
                           $thesisTopic->student->has('course_type') ? $thesisTopic->student->course_type->name : '-',
                           $thesisTopic->student->has('course_level') ? $thesisTopic->student->course_level->name : '-',
                           $thesisTopic->has('starting_year') ? $thesisTopic->starting_year->year : '-', $thesisTopic->semester == 0 ? 'ősz' : 'tavasz'];
            }
        }
        
        $this->response->download("tema_adatok_" . str_replace('/', '_', $year->year) . "_" . $semester . '_' . date("Y-m-d-H-i-s") . '.csv');

        $_header = $headers;
        $_serialize = 'data';
        $_delimiter = "\t";
        $_dataEncoding = 'UTF-8';
        $_csvEncoding = 'UTF-16LE';
        $_bom = true;

        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('_header', '_serialize', 'data', '_delimiter', '_dataEncoding', '_csvEncoding', '_bom'));
    }
    
    /**
     * Téma törlése (végleges)
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();
    
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('Nem létező téma.'));
            return $this->redirect(['action' => 'index']);
        }

        if($this->ThesisTopics->delete($thesisTopic)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        
        return $this->redirect(['action' => 'index']);
    }
}
