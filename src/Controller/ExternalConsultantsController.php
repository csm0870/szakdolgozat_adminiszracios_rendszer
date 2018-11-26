<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ExternalConsultants Controller
 *
 * @property \App\Model\Table\ExternalConsultantsTable $ExternalConsultants
 *
 * @method \App\Model\Entity\ExternalConsultant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExternalConsultantsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $externalConsultants = $this->paginate($this->ExternalConsultants);

        $this->set(compact('externalConsultants'));
    }

    /**
     * View method
     *
     * @param string|null $id External Consultant id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $externalConsultant = $this->ExternalConsultants->get($id, [
            'contain' => ['ThesisTopics']
        ]);

        $this->set('externalConsultant', $externalConsultant);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $externalConsultant = $this->ExternalConsultants->newEntity();
        if ($this->request->is('post')) {
            $externalConsultant = $this->ExternalConsultants->patchEntity($externalConsultant, $this->request->getData());
            if ($this->ExternalConsultants->save($externalConsultant)) {
                $this->Flash->success(__('The external consultant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The external consultant could not be saved. Please, try again.'));
        }
        $this->set(compact('externalConsultant'));
    }

    /**
     * Edit method
     *
     * @param string|null $id External Consultant id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $externalConsultant = $this->ExternalConsultants->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $externalConsultant = $this->ExternalConsultants->patchEntity($externalConsultant, $this->request->getData());
            if ($this->ExternalConsultants->save($externalConsultant)) {
                $this->Flash->success(__('The external consultant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The external consultant could not be saved. Please, try again.'));
        }
        $this->set(compact('externalConsultant'));
    }

    /**
     * Delete method
     *
     * @param string|null $id External Consultant id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $externalConsultant = $this->ExternalConsultants->get($id);
        if ($this->ExternalConsultants->delete($externalConsultant)) {
            $this->Flash->success(__('The external consultant has been deleted.'));
        } else {
            $this->Flash->error(__('The external consultant could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
