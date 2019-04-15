<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Students Controller
 *
 * @property \App\Model\Table\StudentsTable $Students
 *
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StudentsController extends AppController
{
    /**
     * Hallgatók listája
     */
    public function index(){
        $students = $this->Students->find('all', ['contain' => ['Courses', 'CourseTypes', 'CourseLevels']]);                            
        $this->set(compact('students'));
    }
    
    /**
     * Hallgatói adatok módosítása
     *
     * @param string|null $id Hallgató egyedi aznosítója
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $student = $this->Students->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($student)){
            $this->Flash->error(__('A hallgatói adatok nem módosíthatóak.') . ' ' . __('A hallgató nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('Mentés sikeres.'));

                return $this->redirect(['action' => 'edit', $student->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        $courses = $this->Students->Courses->find('list');
        $courseLevels = $this->Students->CourseLevels->find('list');
        $courseTypes = $this->Students->CourseTypes->find('list');
        $this->set(compact('student', 'courses', 'courseLevels', 'courseTypes'));
    }
    
    /**
     * Hallgató részletek
     * 
     * @param type $id Hallgató egyedi azonosítója
     */
    public function details($id = null){
        $student = $this->Students->find('all', ['conditions' => ['Students.id' => $id],
                                                 'contain' => ['Courses', 'CourseTypes', 'CourseLevels', 'FinalExamSubjects',
                                                               'ThesisTopics' => ['ThesisTopicStatuses']]])->first();
    
        if(empty($student)){ //Nem létezik a hallgató
            $this->Flash->error(__('A hallgató részletei nem elérhetőek.') . ' ' . __('Nem létező hallgató.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->loadModel('Years');
        $years = $this->Years->find('list');
        $this->set(compact('student', 'years'));
    }
    
    /**
     * "A hallgató teljesíti a záróvizsgát" rögzítése vagy annak visszavonása (toggle)
     * 
     * @param type $id Hallgató egyedi azonosítója
     * @return type
     */
    public function setPassedFinalExam($id = null){
        $student = $this->Students->find('all', ['conditions' => ['Students.id' => $id],
                                                 'contain' => ['ThesisTopics', 'FinalExamSubjects']])->first();
        
        $ok = true;
        if(empty($student)){ //Nem létezik a hallgató
            $this->Flash->error(__('A hallgatónál nem rögzítheti, hogy teljesítette a záróvizsgát.') . ' ' . __('Nem létező hallgató.'));
            $ok = false;
        }elseif($student->course_id == 1 && $student->final_exam_subjects_status != 3){ //Még nincsenek kiválasztva a ZV tárgyak
            $this->Flash->error(__('A hallgatónál nem rögzítheti, hogy teljesítette a záróvizsgát.') . ' ' . __('A hallgatónak még nincsenek kiválasztva a záróvizsga-tárgyai, így a záróvizsgára nem is mehet.'));
            $ok = false;
        }
        
        if($ok){
            $has_appropriate_thesis = false;
            foreach($student->thesis_topics as $thesisTopic){
                //Ha a téma el van fogadva (a dolgozat), nincs törölve, és fel vannak vive az adatok a Neptun rendszerbe
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted') &&
                   $thesisTopic->deleted !== true &&
                   $thesisTopic->accepted_thesis_data_applyed_to_neptun === true){
                    $has_appropriate_thesis = true;
                    break;
                }
            }
            
            if(!$has_appropriate_thesis){
                $ok = false;
                $this->Flash->error(__('A hallgatónál nem rögzítheti, hogy teljesítette a záróvizsgát.') . ' ' . __('A hallgatónak nincs elfogadott szakdolgozata/diplomamunkája, így záróvizsgára nem is mehet.'));
            }
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $passed_final_exam = $student->passed_final_exam === true ? false : true;
        $student->passed_final_exam = $passed_final_exam;
        if($this->Students->save($student)) $this->Flash->success(__('Mentés sikeres.'));
        else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        
        return $this->redirect(['action' => 'index']);
    }
}
