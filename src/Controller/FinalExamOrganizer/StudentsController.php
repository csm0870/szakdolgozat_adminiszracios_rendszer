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
        $students = $query->matching('ThesisTopics', function($q){ return $q->where(['ThesisTopics.thesis_topic_status_id' => 25, // Elfogadott dolgozat
                                                                                     'ThesisTopics.accepted_thesis_data_applyed_to_neptun' => true /* A dolgozat és a bírálat adatai már fel vannak vive a Neptun rendszerbe */]);})
                          ->contain(['Courses', 'CourseTypes', 'CourseLevels'])
                          //Azon hallgatók, akik nem mérnökinformatikusok, vagy mérnökinformatikus, de van elfogadott záróvizsga tárgyuk
                          ->where(['OR' => ['Students.course_id !=' => 1, 'AND' => ['Students.course_id' => 1, 'Students.final_exam_subjects_status' => 3]]]);
                                                                                     
        $this->set(compact('students'));
    }
    
}
