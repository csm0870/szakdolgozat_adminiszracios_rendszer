<?php
namespace App\Controller\InternalConsultant;

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
     * Záróvizsga-tárgyak lista, csak azon hallgatók, akinek van hozzá témája
     * 
     * @return type
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $students = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.final_exam_subjects_internal_consultant_id' => ($user->has('internal_consultant') ? $user->internal_consultant->id : ''),//Csak a hozzá tartozó hallgatók
                                                                                      'Students.final_exam_subjects_status IN' => [2, 3], //Véglegesített, elfogadott ZV-tárgy kérelmek
                                                                                      'Students.course_id' => 1]]); //Csak mérnökinformatikus
        $this->set(compact('students'));
    }
    
    /**
     * Záróvizsga-tárgyak részletei
     */
    public function details($student_id = null){
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $student_id],
                                                                    'contain' => ['FinalExamSubjects', 'Courses', 'CourseLevels', 'CourseTypes']])->first();
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        
        if(empty($student)){
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A hallgató nem létezik.'));
        }elseif($student->course_id != 1){ //Ha nem mérnökinformatikus
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('Csak mérnökinformatikus hallgatónak lehetnek záróvizsga-tárgyai.'));
        }elseif($student->final_exam_subjects_internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Ha nem az adott belső konzulenshez tartozik
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A hallgató záróvizsga-tárgyai nem Önhöz tartoznak.'));
        }elseif(!in_array($student->final_exam_subjects_status, [2, 3])){ //Nem véglegesített, nem elfogadott
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A záróvizsga-tárgy kérelem nem "Elfogadott" vagy nem "Hallgató által véglegesített" állapotban van.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        if($this->getRequest()->is('post')){
            if($student->final_exam_subjects_status == 2){ //Ha a hallgató által véglegesített állapotban van
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
                                $this->Flash->error(__('Maximum 3 záróvizsga tárgyat adhat hozzá.'));
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
                        $student->final_exam_subjects_status = 3;
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
            }else{
                $this->Flash->error(__('A záróvizsga-tárgyak nem elfogadhatóak.') . ' ' . __('A záróvizsga-tárgy kérelem nem "Hallgató által véglegesített" állapotban van.'));
            }
        }
        
        $years = $this->FinalExamSubjects->Years->find('list');
        $this->set(compact('student', 'years'));
    }
}
