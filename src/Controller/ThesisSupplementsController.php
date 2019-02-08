<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ThesisSupplements Controller
 *
 * @property \App\Model\Table\ThesisSupplementsTable $ThesisSupplements
 *
 * @method \App\Model\Entity\ThesisSupplement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisSupplementsController extends AppController
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
        $thesisSupplements = $this->paginate($this->ThesisSupplements);

        $this->set(compact('thesisSupplements'));
    }

    /**
     * View method
     *
     * @param string|null $id Thesis Supplement id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $thesisSupplement = $this->ThesisSupplements->get($id, [
            'contain' => ['ThesisTopics']
        ]);

        $this->set('thesisSupplement', $thesisSupplement);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $thesisSupplement = $this->ThesisSupplements->newEntity();
        if ($this->request->is('post')) {
            $thesisSupplement = $this->ThesisSupplements->patchEntity($thesisSupplement, $this->request->getData());
            if ($this->ThesisSupplements->save($thesisSupplement)) {
                $this->Flash->success(__('The thesis supplement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis supplement could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->ThesisSupplements->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('thesisSupplement', 'thesisTopics'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Thesis Supplement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $thesisSupplement = $this->ThesisSupplements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $thesisSupplement = $this->ThesisSupplements->patchEntity($thesisSupplement, $this->request->getData());
            if ($this->ThesisSupplements->save($thesisSupplement)) {
                $this->Flash->success(__('The thesis supplement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis supplement could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->ThesisSupplements->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('thesisSupplement', 'thesisTopics'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Thesis Supplement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thesisSupplement = $this->ThesisSupplements->get($id);
        if ($this->ThesisSupplements->delete($thesisSupplement)) {
            $this->Flash->success(__('The thesis supplement has been deleted.'));
        } else {
            $this->Flash->error(__('The thesis supplement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
