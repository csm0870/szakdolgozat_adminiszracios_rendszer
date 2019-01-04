<?php
namespace App\Controller;

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
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Courses', 'CourseLevels', 'CourseTypes', 'Theses', 'Users']
        ];
        $students = $this->paginate($this->Students);

        $this->set(compact('students'));
    }

    /**
     * View method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $student = $this->Students->get($id, [
            'contain' => ['Courses', 'CourseLevels', 'CourseTypes', 'Theses', 'Users', 'FinalExamSubjects']
        ]);

        $this->set('student', $student);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $student = $this->Students->newEntity();
        if ($this->request->is('post')) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('The student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $courses = $this->Students->Courses->find('list', ['limit' => 200]);
        $courseLevels = $this->Students->CourseLevels->find('list', ['limit' => 200]);
        $courseTypes = $this->Students->CourseTypes->find('list', ['limit' => 200]);
        $theses = $this->Students->Theses->find('list', ['limit' => 200]);
        $users = $this->Students->Users->find('list', ['limit' => 200]);
        $this->set(compact('student', 'courses', 'courseLevels', 'courseTypes', 'theses', 'users'));
    }

    /**
     * Edit metódus hallgatónak
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function studentEdit($id = null)
    {
        
        if($this->Auth->user('group_id') == 6){
        
            $data = $this->Students->checkStundentData($this->Auth->user('id'));

            $student = $this->Students->find('all', ['conditions' => ['id' => $data['student_id']]])->first();
            if ($this->request->is(['patch', 'post', 'put'])) {
                $student = $this->Students->patchEntity($student, $this->request->getData());
                if ($this->Students->save($student)) {
                    $this->Flash->success(__('The student has been saved.'));

                    return $this->redirect(['action' => 'studentEdit', $student->id]);
                }
                $this->Flash->error(__('The student could not be saved. Please, try again.'));
            }
            $courses = $this->Students->Courses->find('list');
            $courseLevels = $this->Students->CourseLevels->find('list');
            $courseTypes = $this->Students->CourseTypes->find('list');
            $this->set(compact('student', 'courses', 'courseLevels', 'courseTypes'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $student = $this->Students->get($id);
        if ($this->Students->delete($student)) {
            $this->Flash->success(__('The student has been deleted.'));
        } else {
            $this->Flash->error(__('The student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
