<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Theses Controller
 *
 * @property \App\Model\Table\ThesesTable $Theses
 *
 * @method \App\Model\Entity\Thesis[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ThesisTopics']
        ];
        $theses = $this->paginate($this->Theses);

        $this->set(compact('theses'));
    }

    /**
     * View method
     *
     * @param string|null $id Thesis id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $thesis = $this->Theses->get($id, [
            'contain' => ['ThesisTopics', 'Reviews', 'Consultations', 'Students']
        ]);

        $this->set('thesis', $thesis);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $thesis = $this->Theses->newEntity();
        if ($this->request->is('post')) {
            $thesis = $this->Theses->patchEntity($thesis, $this->request->getData());
            if ($this->Theses->save($thesis)) {
                $this->Flash->success(__('The thesis has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->Theses->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('thesis', 'thesisTopics'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Thesis id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $thesis = $this->Theses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $thesis = $this->Theses->patchEntity($thesis, $this->request->getData());
            if ($this->Theses->save($thesis)) {
                $this->Flash->success(__('The thesis has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->Theses->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('thesis', 'thesisTopics'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Thesis id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thesis = $this->Theses->get($id);
        if ($this->Theses->delete($thesis)) {
            $this->Flash->success(__('The thesis has been deleted.'));
        } else {
            $this->Flash->error(__('The thesis could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
