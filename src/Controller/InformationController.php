<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Information Controller
 *
 * @property \App\Model\Table\InformationTable $Information
 *
 * @method \App\Model\Entity\Information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InformationController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $information = $this->paginate($this->Information);

        $this->set(compact('information'));
    }

    /**
     * View method
     *
     * @param string|null $id Information id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $information = $this->Information->get($id, [
            'contain' => []
        ]);

        $this->set('information', $information);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $information = $this->Information->newEntity();
        if ($this->request->is('post')) {
            $information = $this->Information->patchEntity($information, $this->request->getData());
            if ($this->Information->save($information)) {
                $this->Flash->success(__('The information has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The information could not be saved. Please, try again.'));
        }
        $this->set(compact('information'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Information id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $information = $this->Information->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $information = $this->Information->patchEntity($information, $this->request->getData());
            if ($this->Information->save($information)) {
                $this->Flash->success(__('The information has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The information could not be saved. Please, try again.'));
        }
        $this->set(compact('information'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Information id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $information = $this->Information->get($id);
        if ($this->Information->delete($information)) {
            $this->Flash->success(__('The information has been deleted.'));
        } else {
            $this->Flash->error(__('The information could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
