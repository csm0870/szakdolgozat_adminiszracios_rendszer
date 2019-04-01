<?php
namespace App\Controller\FinalExamOrganizer;

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
     * Azon hallgatók listája, amelyeknek van elfogadott dolgozata, és az adataikat felvitték a neptunba
     */
    public function index(){
        $query = $this->Students->find();
        $students = $query->matching('ThesisTopics', function($q){ return $q->where(['ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted'), // Elfogadott dolgozat
                                                                                     'ThesisTopics.accepted_thesis_data_applyed_to_neptun' => true /* A dolgozat és a bírálat adatai már fel vannak vive a Neptun rendszerbe */,
                                                                                     'ThesisTopics.deleted !=' => true]);})
                          ->contain(['Courses', 'CourseTypes', 'CourseLevels'])
                          //Azon hallgatók, akik nem mérnökinformatikusok, vagy mérnökinformatikus, de van elfogadott záróvizsga tárgyuk
                          ->where(['OR' => ['Students.course_id !=' => 1, 'AND' => ['Students.course_id' => 1, 'Students.final_exam_subjects_status' => 3]], 'Students.passed_final_exam' => false]);
                                                               
        $this->set(compact('students'));
    }
    
    /**
     * Hallgató részletek
     * 
     * @param type $id Hallgató egyedi azonosítója
     */
    public function details($id = null){
        $student = $this->Students->find('all', ['conditions' => ['Students.id' => $id],
                                                 'contain' => ['Courses', 'CourseTypes', 'CourseLevels', 'ThesisTopics', 'FinalExamSubjects']])->first();
    
        $ok = true;
        if(empty($student)){ //Nem létezik a hallgató
            $this->Flash->error(__('A hallgató részletei nem elérhetőek.') . ' ' . __('Nem létező hallgató.'));
            $ok = false;
        }elseif($student->passed_final_exam === true){ //A hallgató már teljesítette a ZV-t
            $this->Flash->error(__('A hallgató részletei nem elérhetőek.') . ' ' . __('A hallgató már teljesítette a záróvizsgát.'));
            $ok = false;
        }elseif($student->course_id == 1 && $student->final_exam_subjects_status != 3){ //Még nincsenek kiválasztva a ZV tárgyak
            $this->Flash->error(__('A hallgató részletei nem elérhetőek.') . ' ' . __('A hallgatónak még nincsenek kiválasztva a záróvizsga-tárgyai.'));
            $ok = false;
        }
        
        if($ok){
            $has_appropriate_thesis = false;
            foreach($student->thesis_topics as $thesisTopic){
                //Ha a téma el van fogadva (a dolgozat), nincs törölve, és fel vannak vive az adatok a Neptun rendszerbe
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted') &&
                   $thesisTopic->deleted === false &&
                   $thesisTopic->accepted_thesis_data_applyed_to_neptun === true){
                    $has_appropriate_thesis = true;
                    break;
                }
            }
            
            if(!$has_appropriate_thesis){
                $ok = false;
                $this->Flash->error(__('A hallgató részletei nem elérhetőek.') . ' ' . __('A hallgatónak nincs elfogadott szakdolgozata/diplomamunkája.'));
            }
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->loadModel('Years');
        $years = $this->Years->find('list');
        $this->set(compact('student', 'years'));
    }
    
    /**
     * "A hallgató teljesíti a záróvizsgát" rögzítése
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
        }elseif($student->passed_final_exam === true){ //A hallgató már teljesítette a ZV-t
            $this->Flash->error(__('A hallgatónál nem rögzítheti, hogy teljesítette a záróvizsgát.') . ' ' . __('A hallgató már teljesítette a záróvizsgát.'));
            $ok = false;
        }elseif($student->course_id == 1 && $student->final_exam_subjects_status != 3){ //Még nincsenek kiválasztva a ZV tárgyak
            $this->Flash->error(__('A hallgatónál nem rögzítheti, hogy teljesítette a záróvizsgát.') . ' ' . __('A hallgatónak még nincsenek kiválasztva a záróvizsga-tárgyai.'));
            $ok = false;
        }
        
        if($ok){
            $has_appropriate_thesis = false;
            foreach($student->thesis_topics as $thesisTopic){
                //Ha a téma el van fogadva (a dolgozat), nincs törölve, és fel vannak vive az adatok a Neptun rendszerbe
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted') &&
                   $thesisTopic->deleted === false &&
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
        
        $student->passed_final_exam = true;
        if($this->Students->save($student)) $this->Flash->success(__('Mentés sikeres.'));
        else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        
        return $this->redirect(['action' => 'index']);
    }
}
