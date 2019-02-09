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
                $thesisTopic->modifiable = true;
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
                $thesisTopic->thesis_topic_status_id = 1;

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

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id]])->first();
        $student = $this->ThesisTopics->Students->find('all',['conditions' => ['Students.user_id' => $this->Auth->user('id')]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('A téma nem módosítható.') . ' ' . __('A szakdolgozat nem Önhöz tartozik.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);

        if ($this->request->is(['patch', 'post', 'put'])) {
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
            $thesisTopic->thesis_topic_status_id = 1;

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

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id]])->first();
        $student = $this->ThesisTopics->Students->find('all',['conditions' => ['Students.user_id' => $this->Auth->user('id')]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A téma nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('A téma nem véglegesíthető.') . ' ' . __('A szakdolgozat nem Önhöz tartozik.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $thesisTopic->modifiable = false;
        //Belső konzulensi döntésre vár
        $thesisTopic->thesis_topic_status_id = 2;

        if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
        else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Szakdolgozat/diplomamunka, mellékletek feltöltése.
     * Záróvizsga tárgyak megadása
     * 
     * @param type $thesis_topic_id
     */
    public function uploadThesis($thesis_topic_id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $student = $this->ThesisTopics->Students->find('all',['conditions' => ['Students.user_id' => $this->Auth->user('id')] ,'contain' => ['FinalExamSubjects']])->first();
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                         'contain' => ['ThesisSupplements']])->first(); 
    
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Diplomamunka/Szakdolgozat nem tölthető fel.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Diplomamunka/Szakdolgozat nem tölthető fel.') . ' ' . __('A szakdolgozat nem Önhöz tartozik.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [12, 13])){ //Nem "A szakdolgozat/diplomamunka a formai követelményeknek megfelelt, feltölthető" státuszban van
            $this->Flash->error(__('Diplomamunka/Szakdolgozat nem tölthető fel.') . ' ' . __('Nem feltöltési státuszban van.'));
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
            
            if($ok && $student->course_id == 1){ //Ha mérnökinformatikus, akkor a záróvizsga tárgyakat is mentjük
                    $final_exam_subjects = $this->getRequest()->getData('final_exam_subjects');
                    
                    $count_of_current_subjects = count($student->final_exam_subjects);
                    $count_of_new_subjects = 0;
                    if(count($final_exam_subjects) == 3){
                        foreach($final_exam_subjects as $subject){
                            if(isset($subject['id'])){ //Ha van ID, akkor azt jelenti, hogy meglévő módosítása van
                                $final_exam_subject = $this->ThesisTopics->Students->FinalExamSubjects->find('all', ['conditions' => ['id' => $subject['id'], 'student_id' => $student->id]])->first();
                                if(empty($final_exam_subject)) break;
                            }else{
                                $count_of_current_subjects++;
                                if($count_of_current_subjects + $count_of_new_subjects > 3){
                                    $this->Flash->error(__('Maximum 3 záróvizgsa tárgyat adhat hozzá.'));
                                    $ok = false;
                                    break;
                                }
                                $final_exam_subject = $this->ThesisTopics->Students->FinalExamSubjects->newEntity();
                            }
                            
                            $final_exam_subject = $this->ThesisTopics->Students->FinalExamSubjects->patchEntity($final_exam_subject, $subject);
                            $final_exam_subject->student_id = $student->id;
                            if(!$this->ThesisTopics->Students->FinalExamSubjects->save($final_exam_subject)){
                                $this->Flash->error(__('Záróvizsga tárgy mentése sikertelen. Próbálja újra!'));
                                $ok = false;
                                break;
                            }
                        }
                    }else{
                        $ok = false;
                        $this->Flash->error(__('Három záróvizsga tárgyat kell megadnia!'));
                    }
            }
            
            if($ok){
                $thesisTopic->thesis_topic_status_id = 13;
                if($this->ThesisTopics->save($thesisTopic)){
                    $this->Flash->success(__('Mentés sikeres.'));
                    return $this->redirect(['action' => 'uploadThesis', $thesisTopic->id]);
                }
                $this->Flash->error(__('Mentés sikertelen. Próbálja újra!!'));
            }
        }
        
        $this->loadModel('Years');
        $years = $this->Years->find('list');
        $this->set(compact('thesisTopic', 'student', 'years', 'final_exam_subjects'));
    }
    
    /**
     * Diplomamunka/Szakdolgozat melléklet letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getThesisSupplements($thesis_topic_id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesis_topic_id]])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $this->Auth->user('id')]])->first();
        
        $ok = true;
        if($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){
            $this->Flash->error(__('A szakdolgozat/diplomamunka nem Önhöz tartozik.'));
            $ok = false;
        }elseif(empty($thesisTopic->thesis_supplements)){
            $this->Flash->error(__('A szakdolgozathoz/diplomamunkához nem tartozik melléklet.'));
            $ok = false;
        }
        
        if(!$ok) return;
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $thesisTopic->thesis_supplements,
                                                   ['download' => true, 'name' => $thesisTopic->thesis_supplements]);

        return $response;
    }
    
    /**
     * Téma véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalizeUploadedThesis($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id], 'contain' => ['ThesisSupplements']])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $this->Auth->user('id')], 'contain' => ['FinalExamSubjects']])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('A szakdolgozat nem Önhöz tartozik.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 13){ //Nem "A szakdolgozat/diplomamunka a formai követelményeknek megfelelt, feltölthető" státuszban van
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Még nem lett feltöltve.'));
            $ok = false;
        }elseif(count($thesisTopic->thesis_supplements) == 0){
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('Nincs feltöltve melléklet.'));
            $ok = false;
        }elseif($student->course_id == 1 && count($student->final_exam_subjects) != 3){ //Mérnökinformatikus és nem három ZV tárgya van
            $this->Flash->error(__('Feltöltés nem véglegesíthető.') . ' ' . __('3 záróvizsga tárgyat kell választania.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        //Belső konzulensi döntésre vár
        $thesisTopic->thesis_topic_status_id = 14;

        if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
        else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
    
    public function details($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id], 'contain' => ['ThesisSupplements']])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $this->Auth->user('id')], 'contain' => ['FinalExamSubjects' => ['Years']]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif($thesisTopic->student_id != (empty($student) ? 'null' : $student->id)){ //Nem a bejelntkezett hallgató szakdolgozata
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A szakdolgozat nem Önhöz tartozik.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 14){ //A szakdolgozati feltöltés nincs véglegesítve
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A szakdolgozat felöltése még nincs véglegesítve.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('thesisTopic', 'student'));
    }
}
