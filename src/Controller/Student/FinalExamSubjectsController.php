<?php
namespace App\Controller\Student;

use App\Controller\AppController;

/**
 * FinalExamSubjects Controller
 *
 * @property \App\Model\Table\FinalExamSubjectsTable $FinalExamSubjects
 *
 * @method \App\Model\Entity\FinalExamSubject[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FinalExamSubjectsController extends AppController
{
    /**
     * Záróvizsga-tárgyak lista
     * 
     * @return type
     */
    public function index(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->FinalExamSubjects->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $ok = true;
        $error_msg = '';
        
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $data['student_id']], 'contain' => ['FinalExamSubjects']])->first();
        //Olyan témák száma, amivel már lehet ZV tárgyak megadni
        $thesisTopics = $this->FinalExamSubjects->Students->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.thesis_topic_status_id IN' => [15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25], 'deleted' => false, 'student_id' => $student->id]]);
        
        if($student->course_id != 1){ //Ha nem mérnökinformatikus
            $ok = false;
            $error_msg = __('Csak mérnökinformatikus hallgató választhat záróvizsga-tárgyakat.');
        }elseif(count($student->final_exam_subjects) == 0 && $thesisTopics->count() == 0){ //Ha nincsenek választott tátrgyai és nincs olyan témája, ami alapján olyan helyzetben lenne, hogy ZV-tárgakat választhatna
            $ok = false;
            $error_msg = __('Nincs olyan állapotban lévő szakdolgozata, ami alapján záróvizsga-tárgyakat választhatna.');
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        if($thesisTopics->count() == 0){ // Ha nincs olyan állapotban lévő témája, ami alapján leadhatna ZV-tárgyakat, de ha itt vagyunk, akkor már van leadva
            $internalConsultants = $this->FinalExamSubjects->Students->FinalExamSubjectsInternalConsultants->find('list', ['conditions' => ['id' => $student->final_exam_subjects_status]]);
            if($internalConsultants->count() == 0){
                $ok = false;
                $error_msg = __('Nincs belső konzulens rendelve a závizsga tárgyaihoz.');
                $this->set(compact('ok', 'error_msg'));
                return;
            }
        }else{
            $internal_consultant_ids = [];
            foreach($thesisTopics as $topic){
                $internal_consultant_ids[] = $topic->internal_consultant_id;
            }
            //Azon belső konzulensek, amelyek a hallgató témáihoz tartoznak
            $internalConsultants = $this->FinalExamSubjects->Students->FinalExamSubjectsInternalConsultants->find('list', ['conditions' => ['id IN' => $internal_consultant_ids]]);        

            if(empty($internalConsultants)){
                $ok = false;
                $error_msg = __('Nincs belső konzulens, akit megjelölhetne a záróvizsga-megjelölő lapon.');
                $this->set(compact('ok', 'error_msg'));
                return;
            }
        }
        
        //Akkor tölthet fel új tárgyakat, ha még eddig nem voltak, illetve, ha van olyan témája amely olyan állapotban van, hogy egyáltalán ZV tárgyválasztás lehet
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            if($student->final_exam_subjects_status == 2){ //Ha már véglegesítve vannak a tárgyak
                $ok = false;
                $this->Flash->error(__('Már nincs lehetősége tárgyakat választani.') . ' ' . __('Már véglegesítve vannak.'));
            }elseif($student->final_exam_subjects_status == 3){ //Ha már el van fogadva a tárgyak
                $ok = false;
                $this->Flash->error(__('Már nincs lehetősége tárgyakat választani.') . ' ' . __('Már el vannak fogadva.'));
            }
            
            if($ok === true){
                $internal_consultant_id = $this->getRequest()->getData('internal_consultant_id');
                
                if(empty($internal_consultant_id)){ //Nincs belső konzulens ID
                    $ok = false;
                    $this->Flash->error(__('Nem választhat záróvizsga-tárgyakat.') . ' ' . __('Belső konzulens választása kötelező.'));
                }elseif(!in_array($internal_consultant_id, $internal_consultant_ids)){ //Nincs a belső konzulens ID-k között a kérésben lévő ID
                    $ok = false;
                    $this->Flash->error(__('Nem választhat záróvizsga-tárgyakat.') . ' ' . __('A választott belső konzulens nincs olyan állapotú témához rendelve, amely alapján záróvizsga-tárgyakat választhatna.'));
                }
            }
            
            if($ok === false)  return $this->redirect(['action' => 'index']);
            
            $final_exam_subjects = $this->getRequest()->getData('final_exam_subjects');
                    
            $error_message = ''; //Hibaüzenet, ha nem sikerült a ZV-tárgyak mentése
            $count_of_current_subjects = count($student->final_exam_subjects);
            $count_of_new_subjects = 0; //Új záróvizsga-tárgyak száma
            $save_ok = true; //Záróvizsga tárgyak mentése sikeres-e
            if(count($final_exam_subjects) == 3){
                foreach($final_exam_subjects as $number => $subject){
                    if(isset($subject['id'])){ //Ha van ID, akkor azt jelenti, hogy meglévő módosítása van
                        $final_exam_subject = $this->FinalExamSubjects->find('all', ['conditions' => ['id' => $subject['id'], 'student_id' => $student->id]])->first();
                        if(empty($final_exam_subject)) continue;
                    }else{
                        $count_of_current_subjects++;
                        if($count_of_current_subjects + $count_of_new_subjects > 3){
                            $this->Flash->error(__('Maximum 3 záróvizgsa tárgyat adhat hozzá.'));
                            break;
                        }
                        $final_exam_subject = $this->FinalExamSubjects->newEntity();
                    }

                    $final_exam_subject = $this->FinalExamSubjects->patchEntity($final_exam_subject, $subject);
                    $final_exam_subject->student_id = $student->id;
                    if(!$this->FinalExamSubjects->save($final_exam_subject)){
                        $save_ok = false;
                        $errors = $final_exam_subject->getErrors();
                        if(!empty($errors)){
                            foreach($errors as $error){
                                if(is_array($error)){
                                    foreach($error as $err){
                                        $error_message.= ' ' . $err;
                                    }
                                }else{
                                    $error_message.= ' ' . $error;
                                }
                            }
                        }
                        
                        $final_exam_subject_error_number = $number;
                        $this->set(compact('final_exam_subject_error_number'));
                        break;
                    }
                }
                
                if($save_ok === true){
                    $student->final_exam_subjects_status = 1;
                    $student->final_exam_subjects_internal_consultant_id = $internal_consultant_id;
                    if($this->FinalExamSubjects->Students->save($student)){
                        $this->Flash->success(__('Mentés sikeres.'));
                        return $this->redirect(['action' => 'index']);
                    }else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
                }else{
                    $this->Flash->error(__('Záróvizsga tárgy mentése sikertelen. Próbálja újra!') . ' ' . $error_message);
                }
            }else{
                $this->Flash->error(__('Három záróvizsga tárgyat kell megadnia!'));
            }
        }
        
        //Hallgató lekérése újra, hogy, ha van mentett ZV-tárgy, akkor azok bekerüljenek a mezőkbe sikertelen mentés után is (olyan esetben, ha pl. 1-et el tudunk menteni, de egy másikat nem, így az elmentett adatok jó helyre kerülnek)
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $data['student_id']], 'contain' => ['FinalExamSubjects']])->first();
        $years = $this->FinalExamSubjects->Years->find('list');
        $this->set(compact('student', 'ok', 'error_msg', 'years', 'internalConsultants'));
    }
    
    /**
     * Záróvizsga-tárgyak véglegesítése
     * 
     * @return type
     */
    public function finalize(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->FinalExamSubjects->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $ok = true;
        $error_msg = '';
        
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $data['student_id']], 'contain' => ['FinalExamSubjects']])->first();
        
        if($student->course_id != 1){ //Ha nem mérnökinformatikus
            $ok = false;
            $error_msg = __('Záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Csak mérnökinformatikus hallgatónak lehetnek záróvizsga-tárgyai.');
        }elseif(count($student->final_exam_subjects) != 3){ //Ha nem három ZV-tárgy van
            $ok = false;
            $error_msg = __('Záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Három záróvizsga-tárgyat kell választani.');
        }elseif($student->final_exam_subjects_status == 2){ //Ha már véglegesítve vannak a tárgyak
            $ok = false;
            $error_msg = __('Záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Már véglegesítve vannak.');
        }elseif($student->final_exam_subjects_status == 3){ //Ha már el van fogadva a tárgyak
            $ok = false;
            $error_msg = __('Záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Már el vannak fogadva.');
        }
        
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $student->final_exam_subjects_status = 2;
        if($this->FinalExamSubjects->Students->save($student)){
            $this->Flash->success(__('Véglegesítés sikeres.'));
        }else{
            $this->Flash->error(__('Véglegesítés sikeretlen. Próbálja újra!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
