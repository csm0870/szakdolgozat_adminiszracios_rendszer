<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConsultationOccasions Controller
 *
 * @property \App\Model\Table\ConsultationOccasionsTable $ConsultationOccasions
 *
 * @method \App\Model\Entity\ConsultationOccasion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConsultationOccasionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Consultations']
        ];
        $consultationOccasions = $this->paginate($this->ConsultationOccasions);

        $this->set(compact('consultationOccasions'));
    }

    /**
     * View method
     *
     * @param string|null $id Consultation Occasion id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $consultationOccasion = $this->ConsultationOccasions->get($id, [
            'contain' => ['Consultations']
        ]);

        $this->set('consultationOccasion', $consultationOccasion);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $consultationOccasion = $this->ConsultationOccasions->newEntity();
        if ($this->request->is('post')) {
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->request->getData());
            if ($this->ConsultationOccasions->save($consultationOccasion)) {
                $this->Flash->success(__('The consultation occasion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The consultation occasion could not be saved. Please, try again.'));
        }
        $consultations = $this->ConsultationOccasions->Consultations->find('list', ['limit' => 200]);
        $this->set(compact('consultationOccasion', 'consultations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Consultation Occasion id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $consultationOccasion = $this->ConsultationOccasions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->request->getData());
            if ($this->ConsultationOccasions->save($consultationOccasion)) {
                $this->Flash->success(__('The consultation occasion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The consultation occasion could not be saved. Please, try again.'));
        }
        $consultations = $this->ConsultationOccasions->Consultations->find('list', ['limit' => 200]);
        $this->set(compact('consultationOccasion', 'consultations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Consultation Occasion id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $consultationOccasion = $this->ConsultationOccasions->get($id);
        if ($this->ConsultationOccasions->delete($consultationOccasion)) {
            $this->Flash->success(__('The consultation occasion has been deleted.'));
        } else {
            $this->Flash->error(__('The consultation occasion could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
