<?php
namespace App\Controller\Admin;

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
     * Záróvizsga-tárgyak részletei és mentés/véglegesítés
     * 
     * @param type $student_id Hallgató egyedi azonosítója
     * @return type
     */
    public function details($student_id = null){
        $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $student_id],
                                                                    'contain' => ['FinalExamSubjects']])->first();
        
        $ok = true;
        if(empty($student)){
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A hallgató nem létezik.'));
            return $this->redirect(['controller' => 'Students', 'action' => 'index']);
        }elseif($student->course_id != 1){ //Ha nem mérnökinformatikus
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('Csak mérnökinformatikus hallgatónak lehetnek záróvizsga-tárgyai.'));
        }elseif(!in_array($student->final_exam_subjects_status, [2, 3])){ //Nem véglegesített, nem elfogadott
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A záróvizsga-tárgy kérelem nem "Elfogadott" vagy nem "Hallgató által véglegesített" állapotban van.'));
        }
        
        if($ok === false) return $this->redirect(['controller' => 'Students', 'action' => 'details', $student->id]);
        
        if($this->getRequest()->is('post')){
            $is_finalize = $this->getRequest()->getData('is_finalize');
            //Véglegesítésről van-e szó
            if(!empty($is_finalize) && $is_finalize == 1){
                if($student->final_exam_subjects_status != 2){ //Ha még nincs véglegesítve a hallgató tárgy javaslata
                    $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetőek.') . ' ' . __('Nem véglegesítésre váró állapotban vannak.'));
                    $ok = false;
                }
            }else{
                if($student->final_exam_subjects_status != 2 && $student->final_exam_subjects_status != 3){ //Ha még nincs véglegesítve a hallgató tárgy javaslata, vagy nincs végelgesítve
                    $this->Flash->error(__('A záróvizsga-tárgyak nem menthetőek.') . ' ' . __('A hallgató még nem választott záróvizsga-tárgyakat.'));
                    $ok = false;
                }
            }
            
            if(!$ok) return $this->redirect(['controller' => 'Students', 'action' => 'details', $student->id]);
            
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
                    if(!empty($is_finalize) && $is_finalize == 1) //Ha véglegesítésről van szó
                        $student->final_exam_subjects_status = 3;
                    
                    if($this->FinalExamSubjects->Students->save($student)){
                        $this->Flash->success(__('Mentés sikeres.'));
                        return $this->redirect(['action' => 'details', $student->id]);
                    }else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
                }else{
                    $this->Flash->error(__('Záróvizsga tárgy mentése sikertelen. Próbálja újra!') . ' ' . $error_message);
                }
            }else{
                $this->Flash->error(__('Három záróvizsga tárgyat kell megadnia!'));
            }
        }
        
        $years = $this->FinalExamSubjects->Years->find('list', ['order' => ['year' => 'ASC']]);
        $this->set(compact('student', 'years'));
    }
}
