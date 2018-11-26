<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InternalConsultants Controller
 *
 * @property \App\Model\Table\InternalConsultantsTable $InternalConsultants
 *
 * @method \App\Model\Entity\InternalConsultant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InternalConsultantsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Departments', 'Users']
        ];
        $internalConsultants = $this->paginate($this->InternalConsultants);

        $this->set(compact('internalConsultants'));
    }

    /**
     * View method
     *
     * @param string|null $id Internal Consultant id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $internalConsultant = $this->InternalConsultants->get($id, [
            'contain' => ['Departments', 'Users', 'ThesisTopics']
        ]);

        $this->set('internalConsultant', $internalConsultant);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $internalConsultant = $this->InternalConsultants->newEntity();
        if ($this->request->is('post')) {
            $internalConsultant = $this->InternalConsultants->patchEntity($internalConsultant, $this->request->getData());
            if ($this->InternalConsultants->save($internalConsultant)) {
                $this->Flash->success(__('The internal consultant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The internal consultant could not be saved. Please, try again.'));
        }
        $departments = $this->InternalConsultants->Departments->find('list', ['limit' => 200]);
        $users = $this->InternalConsultants->Users->find('list', ['limit' => 200]);
        $this->set(compact('internalConsultant', 'departments', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Internal Consultant id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $internalConsultant = $this->InternalConsultants->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $internalConsultant = $this->InternalConsultants->patchEntity($internalConsultant, $this->request->getData());
            if ($this->InternalConsultants->save($internalConsultant)) {
                $this->Flash->success(__('The internal consultant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The internal consultant could not be saved. Please, try again.'));
        }
        $departments = $this->InternalConsultants->Departments->find('list', ['limit' => 200]);
        $users = $this->InternalConsultants->Users->find('list', ['limit' => 200]);
        $this->set(compact('internalConsultant', 'departments', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Internal Consultant id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $internalConsultant = $this->InternalConsultants->get($id);
        if ($this->InternalConsultants->delete($internalConsultant)) {
            $this->Flash->success(__('The internal consultant has been deleted.'));
        } else {
            $this->Flash->error(__('The internal consultant could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
