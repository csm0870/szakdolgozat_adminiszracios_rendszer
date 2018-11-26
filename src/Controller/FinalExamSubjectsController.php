<?php
namespace App\Controller;

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
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Students']
        ];
        $finalExamSubjects = $this->paginate($this->FinalExamSubjects);

        $this->set(compact('finalExamSubjects'));
    }

    /**
     * View method
     *
     * @param string|null $id Final Exam Subject id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $finalExamSubject = $this->FinalExamSubjects->get($id, [
            'contain' => ['Students']
        ]);

        $this->set('finalExamSubject', $finalExamSubject);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $finalExamSubject = $this->FinalExamSubjects->newEntity();
        if ($this->request->is('post')) {
            $finalExamSubject = $this->FinalExamSubjects->patchEntity($finalExamSubject, $this->request->getData());
            if ($this->FinalExamSubjects->save($finalExamSubject)) {
                $this->Flash->success(__('The final exam subject has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The final exam subject could not be saved. Please, try again.'));
        }
        $students = $this->FinalExamSubjects->Students->find('list', ['limit' => 200]);
        $this->set(compact('finalExamSubject', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Final Exam Subject id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $finalExamSubject = $this->FinalExamSubjects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $finalExamSubject = $this->FinalExamSubjects->patchEntity($finalExamSubject, $this->request->getData());
            if ($this->FinalExamSubjects->save($finalExamSubject)) {
                $this->Flash->success(__('The final exam subject has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The final exam subject could not be saved. Please, try again.'));
        }
        $students = $this->FinalExamSubjects->Students->find('list', ['limit' => 200]);
        $this->set(compact('finalExamSubject', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Final Exam Subject id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $finalExamSubject = $this->FinalExamSubjects->get($id);
        if ($this->FinalExamSubjects->delete($finalExamSubject)) {
            $this->Flash->success(__('The final exam subject has been deleted.'));
        } else {
            $this->Flash->error(__('The final exam subject could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
