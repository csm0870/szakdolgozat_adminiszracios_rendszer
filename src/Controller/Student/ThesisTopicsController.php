<?php
namespace App\Controller\Student;

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

    /**
     * Témalista
     * 
     * @return type
     */
    public function index(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $can_fill_in_topic = false;
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();

        //Kitöltési időszak ellenőrzése
        if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
            $today = date('Y-m-d');

            $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
            $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

            if($today >= $start_date && $today <= $end_date){
                $can_fill_in_topic = true;
            }
        }

        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $data['student_id'], 'deleted !=' => true],
                                                          'order' => ['created' => 'DESC'],
                                                          'contain' => ['ThesisTopicStatuses']]);

        $can_add_topic = $this->ThesisTopics->Students->canAddTopic($data['student_id']);

        $this->set(compact('can_fill_in_topic', 'can_add_topic', 'thesisTopics'));
    }
    
    /**
     * Téma hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        if(!$this->ThesisTopics->Students->canAddTopic($data['student_id'])){
            $this->Flash->error(__('Nem adhat hozzá új témát!'));
            return $this->redirect(['action' => 'index']);
        }

        $can_fill_in_topic = false;
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();
        
        //Leadhatósági időszak ellenőrzése
        if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
            $today = date('Y-m-d');

            $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
            $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

            if($today >= $start_date && $today <= $end_date){
                $can_fill_in_topic = true;
            }
        }

        if($can_fill_in_topic === true){
            $thesisTopic = $this->ThesisTopics->newEntity();
            if ($this->request->is('post')) {
                $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
                $thesisTopic->student_id = $data['student_id'];
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
                
                //Véglegesítésre vár
                $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize');

                if ($this->ThesisTopics->save($thesisTopic)) {
                    $this->Flash->success(__('Mentés sikeres.'));

                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }

            $this->loadModel('Years');
            $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
            $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
            $languages = $this->ThesisTopics->Languages->find('list');
            $this->set(compact('thesisTopic', 'internalConsultants', 'years', 'languages'));
        }

        $this->set(compact('can_fill_in_topic'));
    }

    /**
     * Téma szerkesztése
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['OfferedTopics']])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != $data['student_id']){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem Önhöz tartozik.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){ //Nem véglegesítésre vár
            $this->Flash->error(__('A téma az állapota alapján nem módosítható.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);

        if($this->request->is(['patch', 'post', 'put'])){
            $request_data = $this->getRequest()->getData();
            $can_change_external_consultant = true;
            //Ha témaájánlatok közül választott témáról van szó, akkor a megfelelő mezőket, amiket nem módosíthat, "töröljük"
            if($thesisTopic->has('offered_topic')){
                unset($request_data['internal_consultant_id']);
                
                //Ha témalefoglalásról van szó
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){
                    unset($request_data['title']);
                    unset($request_data['description']);
                    unset($request_data['is_thesis']);
                    unset($request_data['language_id']);
                    unset($request_data['confidential']);

                    //Ha a témaajánlathoz tartozik külső konzulens
                    if($thesisTopic->offered_topic->has_external_consultant === true){
                        unset($request_data['external_consultant_name']);
                        unset($request_data['external_consultant_position']);
                        unset($request_data['external_consultant_workplace']);
                        unset($request_data['external_consultant_email']);
                        unset($request_data['external_consultant_phone_number']);
                        unset($request_data['external_consultant_address']);
                        unset($request_data['cause_of_no_external_consultant']);
                        $thesisTopic->cause_of_no_external_consultant = null;
                        $can_change_external_consultant = false;
                    }
                }
            }
            
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $request_data);
            $thesisTopic->student_id = $data['student_id'];
            $has_external_consultant = $this->getRequest()->getData('has_external_consultant');
            
            if($can_change_external_consultant === true){
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
     * Téma részletek
     * 
     * @param type $id
     * @return type
     */
    public function details($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'ThesisSupplements',
                                                                       'StartingYears', 'ExpectedEndingYears', 'Languages', 'Reviews']])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részletek nem elérhetőek.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif($thesisTopic->student_id != $data['student_id']){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Részletek nem elérhetőek.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $can_fill_in_topic = false;
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();

        //Kitöltési időszak ellenőrzése
        if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
            $today = date('Y-m-d');

            $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
            $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

            if($today >= $start_date && $today <= $end_date){
                $can_fill_in_topic = true;
            }
        }
        
        $this->set(compact('thesisTopic', 'can_fill_in_topic'));
    }
    
    /**
     * Téma véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalizeThesisTopic($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id, 'deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != $data['student_id']){ //Nem a bejelentkezett hallgató szakdolgozata
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem Önhöz tartozik.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){ //Nem véglegesítésre vár
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem véglegesíthető állapotban van.'));
            $ok = false;
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
     * Szakdolgozat/diplomamunka, mellékletek feltöltése.
     * 
     * @param type $thesis_topic_id
     */
    public function uploadThesisSupplements($thesis_topic_id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                         'contain' => ['ThesisSupplements', 'ThesisTopicStatuses']])->first(); 
    
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Dolgozat nem tölthető fel.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->student_id != $data['student_id']){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Dolgozat nem tölthető fel.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected')])){
            $this->Flash->error(__('Dolgozat nem tölthető fel.') . ' ' . __('Nem feltöltési státuszban van.'));
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
                $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement');
                if($this->ThesisTopics->save($thesisTopic)){
                    $this->Flash->success(__('Mentés sikeres.'));
                    return $this->redirect(['action' => 'uploadThesisSupplements', $thesisTopic->id]);
                }
                $this->Flash->error(__('Mentés sikertelen. Próbálja újra!!'));
            }
        }
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Dolgozat melléklet feltöltésének véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalizeUploadedThesisSupplements($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id, 'deleted !=' => true], 'contain' => ['ThesisSupplements']])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $this->Auth->user('id')], 'contain' => ['FinalExamSubjects']])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
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

        return $this->redirect(['action' => 'details', $thesisTopic->id]);
    }
    
    /**
     * Foglalás visszavonása
     * 
     * @param type $id
     */
    public function cancelBooking($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id, 'ThesisTopics.deleted !=' => true]])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $this->Auth->user('id')]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A foglalás nem vonható vissza.') . ' ' . __('Nem létezik a dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('A foglalás nem vonható vissza.') . ' ' . __('A téma nem Önhöz tartozik.'));
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
     * Pdf generálás CakdePdf pluginnal
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function exportPdf($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'ThesisTopics.deleted !=' => true],
                                                         'contain' => ['Students' => ['Courses', 'CourseLevels', 'CourseTypes'],
                                                                       'InternalConsultants' => ['Departments', 'InternalConsultantPositions'],
                                                                       'StartingYears', 'ExpectedEndingYears', 'Languages']])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A PDF nem elérhető.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != $data['student_id']){
            $this->Flash->error(__('A PDF nem elérhető.') . ' ' . __('A téma nem Önhöz tartozik.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')])){
            $this->Flash->error(__('A PDF nem elérhető.') . ' ' . __('A téma még nem lett leadva.'));
            $ok = false;
        }
            
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'title' => "feladatkiiro_lap-" . date("Y-m-d-H-i-s"),
                'margin' => [
                    'bottom' => 12,
                    'left' => 12,
                    'right' => 12,
                    'top' => 12
                ]
            ]
        ]);

        $this->set(compact('thesisTopic'));
    }
}
