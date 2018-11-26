<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Reviewers Controller
 *
 * @property \App\Model\Table\ReviewersTable $Reviewers
 *
 * @method \App\Model\Entity\Reviewer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $reviewers = $this->paginate($this->Reviewers);

        $this->set(compact('reviewers'));
    }

    /**
     * View method
     *
     * @param string|null $id Reviewer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reviewer = $this->Reviewers->get($id, [
            'contain' => ['Users', 'Reviews']
        ]);

        $this->set('reviewer', $reviewer);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reviewer = $this->Reviewers->newEntity();
        if ($this->request->is('post')) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)) {
                $this->Flash->success(__('The reviewer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reviewer could not be saved. Please, try again.'));
        }
        $users = $this->Reviewers->Users->find('list', ['limit' => 200]);
        $this->set(compact('reviewer', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reviewer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reviewer = $this->Reviewers->get($id, [
            'contain' => ['Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)) {
                $this->Flash->success(__('The reviewer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reviewer could not be saved. Please, try again.'));
        }
        $users = $this->Reviewers->Users->find('list', ['limit' => 200]);
        $this->set(compact('reviewer', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reviewer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reviewer = $this->Reviewers->get($id);
        if ($this->Reviewers->delete($reviewer)) {
            $this->Flash->success(__('The reviewer has been deleted.'));
        } else {
            $this->Flash->error(__('The reviewer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
