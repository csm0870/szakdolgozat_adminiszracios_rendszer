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
                                                                                      'Students.final_exam_subjects_status IN' => [2, 3, 4], //Véglegesített, elfogadott, elutasított ZV-tárgy kérelmek
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
        }elseif(!in_array($student->final_exam_subjects_status, [2, 3, 4])){ //Nem véglegesített, nem elfogadott, vagy nem elutasított státuszú
            $ok = false;
            $this->Flash->error(__('A záróvizsga-tárgyak részletei nem elérhetőek.') . ' ' . __('A záróvizsga-tárgy kérelem nem "Elfogadott", nem "Elutasított", vagy nem "Véglegesített" állapotban van.'));
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        $years = $this->FinalExamSubjects->Years->find('list');
        $this->set(compact('student', 'years'));
    }
    
    /**
     * Táma elfogadása vagy elutasítása
     * @return type
     */
    public function accept(){
        if($this->getRequest()->is('post')){
            $student_id = $this->getRequest()->getData('student_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->loadModel('Users');
            
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);

            $student = $this->FinalExamSubjects->Students->find('all', ['conditions' => ['Students.id' => $student_id], 'contain' => ['FinalExamSubjects']])->first();
        
            $ok = true;
            if(empty($student)){
                $ok = false;
                $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('A hallgató nem létezik.'));
            }elseif($student->final_exam_subjects_internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Ha nem az adott belső konzulenshez tartozik
                $ok = false;
                $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('A hallgató záróvizsga-tárgyai nem Önhöz tartoznak.'));
            }elseif($student->course_id != 1){ //Ha nem mérnökinformatikus
                $ok = false;
               $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Csak mérnökinformatikus hallgatónak lehetnek záróvizsga-tárgyai.'));
            }elseif(count($student->final_exam_subjects) != 3){ //Ha nem három ZV-tárgy van
                $ok = false;
                $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('Három záróvizsga-tárgyat kell választani.'));
            }elseif($student->final_exam_subjects_status != 2){ //Nem véglegesített, nem elfogadott, vagy nem elutasított státuszú
                $ok = false;
                $this->Flash->error(__('A záróvizsga-tárgyak nem véglegesíthetők.') . ' ' . __('A záróvizsga-tárgy kérelem nem "Véglegesítésre vár" állapotban van.'));
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
            
            $student->final_exam_subjects_status = $accepted == 0 ? 4 : 3;
            if($this->FinalExamSubjects->Students->save($student)){
                $this->Flash->success(__(($accepted == 0 ? 'Elutasítás' : 'Elfogadás') . ' sikeres.'));
            }else{
                $this->Flash->error(__(($accepted == 0 ? 'Elutasítás' : 'Elfogadás') . ' sikeretlen. Próbálja újra!'));
            }
        }
        
        return $this->redirect(['action' => 'details', $student->id]);
    }
}
