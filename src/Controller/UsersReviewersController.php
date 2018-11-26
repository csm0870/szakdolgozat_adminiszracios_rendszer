<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UsersReviewers Controller
 *
 * @property \App\Model\Table\UsersReviewersTable $UsersReviewers
 *
 * @method \App\Model\Entity\UsersReviewer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersReviewersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Reviewers']
        ];
        $usersReviewers = $this->paginate($this->UsersReviewers);

        $this->set(compact('usersReviewers'));
    }

    /**
     * View method
     *
     * @param string|null $id Users Reviewer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usersReviewer = $this->UsersReviewers->get($id, [
            'contain' => ['Users', 'Reviewers']
        ]);

        $this->set('usersReviewer', $usersReviewer);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usersReviewer = $this->UsersReviewers->newEntity();
        if ($this->request->is('post')) {
            $usersReviewer = $this->UsersReviewers->patchEntity($usersReviewer, $this->request->getData());
            if ($this->UsersReviewers->save($usersReviewer)) {
                $this->Flash->success(__('The users reviewer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users reviewer could not be saved. Please, try again.'));
        }
        $users = $this->UsersReviewers->Users->find('list', ['limit' => 200]);
        $reviewers = $this->UsersReviewers->Reviewers->find('list', ['limit' => 200]);
        $this->set(compact('usersReviewer', 'users', 'reviewers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Users Reviewer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersReviewer = $this->UsersReviewers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersReviewer = $this->UsersReviewers->patchEntity($usersReviewer, $this->request->getData());
            if ($this->UsersReviewers->save($usersReviewer)) {
                $this->Flash->success(__('The users reviewer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users reviewer could not be saved. Please, try again.'));
        }
        $users = $this->UsersReviewers->Users->find('list', ['limit' => 200]);
        $reviewers = $this->UsersReviewers->Reviewers->find('list', ['limit' => 200]);
        $this->set(compact('usersReviewer', 'users', 'reviewers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Users Reviewer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usersReviewer = $this->UsersReviewers->get($id);
        if ($this->UsersReviewers->delete($usersReviewer)) {
            $this->Flash->success(__('The users reviewer has been deleted.'));
        } else {
            $this->Flash->error(__('The users reviewer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
