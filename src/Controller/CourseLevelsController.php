<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CourseLevels Controller
 *
 * @property \App\Model\Table\CourseLevelsTable $CourseLevels
 *
 * @method \App\Model\Entity\CourseLevel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CourseLevelsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $courseLevels = $this->paginate($this->CourseLevels);

        $this->set(compact('courseLevels'));
    }

    /**
     * View method
     *
     * @param string|null $id Course Level id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $courseLevel = $this->CourseLevels->get($id, [
            'contain' => ['Students']
        ]);

        $this->set('courseLevel', $courseLevel);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $courseLevel = $this->CourseLevels->newEntity();
        if ($this->request->is('post')) {
            $courseLevel = $this->CourseLevels->patchEntity($courseLevel, $this->request->getData());
            if ($this->CourseLevels->save($courseLevel)) {
                $this->Flash->success(__('The course level has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course level could not be saved. Please, try again.'));
        }
        $this->set(compact('courseLevel'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Course Level id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $courseLevel = $this->CourseLevels->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $courseLevel = $this->CourseLevels->patchEntity($courseLevel, $this->request->getData());
            if ($this->CourseLevels->save($courseLevel)) {
                $this->Flash->success(__('The course level has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course level could not be saved. Please, try again.'));
        }
        $this->set(compact('courseLevel'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Course Level id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $courseLevel = $this->CourseLevels->get($id);
        if ($this->CourseLevels->delete($courseLevel)) {
            $this->Flash->success(__('The course level has been deleted.'));
        } else {
            $this->Flash->error(__('The course level could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
