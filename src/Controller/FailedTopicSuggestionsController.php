<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * FailedTopicSuggestions Controller
 *
 * @property \App\Model\Table\FailedTopicSuggestionsTable $FailedTopicSuggestions
 *
 * @method \App\Model\Entity\FailedTopicSuggestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FailedTopicSuggestionsController extends AppController
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
        $failedTopicSuggestions = $this->paginate($this->FailedTopicSuggestions);

        $this->set(compact('failedTopicSuggestions'));
    }

    /**
     * View method
     *
     * @param string|null $id Failed Topic Suggestion id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $failedTopicSuggestion = $this->FailedTopicSuggestions->get($id, [
            'contain' => ['ThesisTopics']
        ]);

        $this->set('failedTopicSuggestion', $failedTopicSuggestion);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $failedTopicSuggestion = $this->FailedTopicSuggestions->newEntity();
        if ($this->request->is('post')) {
            $failedTopicSuggestion = $this->FailedTopicSuggestions->patchEntity($failedTopicSuggestion, $this->request->getData());
            if ($this->FailedTopicSuggestions->save($failedTopicSuggestion)) {
                $this->Flash->success(__('The failed topic suggestion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The failed topic suggestion could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->FailedTopicSuggestions->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('failedTopicSuggestion', 'thesisTopics'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Failed Topic Suggestion id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $failedTopicSuggestion = $this->FailedTopicSuggestions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $failedTopicSuggestion = $this->FailedTopicSuggestions->patchEntity($failedTopicSuggestion, $this->request->getData());
            if ($this->FailedTopicSuggestions->save($failedTopicSuggestion)) {
                $this->Flash->success(__('The failed topic suggestion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The failed topic suggestion could not be saved. Please, try again.'));
        }
        $thesisTopics = $this->FailedTopicSuggestions->ThesisTopics->find('list', ['limit' => 200]);
        $this->set(compact('failedTopicSuggestion', 'thesisTopics'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Failed Topic Suggestion id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $failedTopicSuggestion = $this->FailedTopicSuggestions->get($id);
        if ($this->FailedTopicSuggestions->delete($failedTopicSuggestion)) {
            $this->Flash->success(__('The failed topic suggestion has been deleted.'));
        } else {
            $this->Flash->error(__('The failed topic suggestion could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
