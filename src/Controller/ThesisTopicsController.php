<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{

    public function topic(){
        
    }
    
    public function topicForm(){
        
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ExternalConsultants', 'InternalConsultants', 'ThesisTypes']
        ];
        $thesisTopics = $this->paginate($this->ThesisTopics);

        $this->set(compact('thesisTopics'));
    }

    /**
     * View method
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $thesisTopic = $this->ThesisTopics->get($id, [
            'contain' => ['ExternalConsultants', 'InternalConsultants', 'ThesisTypes', 'FailedTopicSuggestions', 'Theses']
        ]);

        $this->set('thesisTopic', $thesisTopic);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $thesisTopic = $this->ThesisTopics->newEntity();
        if ($this->request->is('post')) {
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
            if ($this->ThesisTopics->save($thesisTopic)) {
                $this->Flash->success(__('The thesis topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis topic could not be saved. Please, try again.'));
        }
        $externalConsultants = $this->ThesisTopics->ExternalConsultants->find('list', ['limit' => 200]);
        $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list', ['limit' => 200]);
        $thesisTypes = $this->ThesisTopics->ThesisTypes->find('list', ['limit' => 200]);
        $this->set(compact('thesisTopic', 'externalConsultants', 'internalConsultants', 'thesisTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $thesisTopic = $this->ThesisTopics->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
            if ($this->ThesisTopics->save($thesisTopic)) {
                $this->Flash->success(__('The thesis topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The thesis topic could not be saved. Please, try again.'));
        }
        $externalConsultants = $this->ThesisTopics->ExternalConsultants->find('list', ['limit' => 200]);
        $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list', ['limit' => 200]);
        $thesisTypes = $this->ThesisTopics->ThesisTypes->find('list', ['limit' => 200]);
        $this->set(compact('thesisTopic', 'externalConsultants', 'internalConsultants', 'thesisTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->get($id);
        if ($this->ThesisTopics->delete($thesisTopic)) {
            $this->Flash->success(__('The thesis topic has been deleted.'));
        } else {
            $this->Flash->error(__('The thesis topic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
