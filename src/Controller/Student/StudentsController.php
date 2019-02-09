<?php
namespace App\Controller\Student;

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
     * Edit metódus hallgatónak
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        
        $can_modify_data = $this->Students->canModifyData($data['student_id']);
        if($can_modify_data === false){//Ha adhat hozzá témát
            $this->Flash->error(__('Nem módosíthatja az adatait, mert van elfogadott vagy elfogadási folyamatban lévő témája.'));
        }
        
        $student = $this->Students->find('all', ['conditions' => ['id' => $data['student_id']]])->first();
        if($can_modify_data === true && $this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('Mentés sikeres.'));

                return $this->redirect(['action' => 'edit', $student->id]);
            }
            $this->Flash->error(__('Mentés sikertelen.'));
        }
        $courses = $this->Students->Courses->find('list');
        $courseLevels = $this->Students->CourseLevels->find('list');
        $courseTypes = $this->Students->CourseTypes->find('list');
        $this->set(compact('student', 'courses', 'courseLevels', 'courseTypes', 'can_modify_data'));
    }
}
