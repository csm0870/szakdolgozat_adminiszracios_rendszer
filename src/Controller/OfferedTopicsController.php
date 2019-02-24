<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * OfferedTopics Controller
 *
 * @property \App\Model\Table\OfferedTopicsTable $OfferedTopics
 *
 * @method \App\Model\Entity\OfferedTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OfferedTopicsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['InternalConsultants', 'Students']
        ];
        $offeredTopics = $this->paginate($this->OfferedTopics);

        $this->set(compact('offeredTopics'));
    }

    /**
     * View method
     *
     * @param string|null $id Offered Topic id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $offeredTopic = $this->OfferedTopics->get($id, [
            'contain' => ['InternalConsultants', 'Students']
        ]);

        $this->set('offeredTopic', $offeredTopic);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $offeredTopic = $this->OfferedTopics->newEntity();
        if ($this->request->is('post')) {
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            if ($this->OfferedTopics->save($offeredTopic)) {
                $this->Flash->success(__('The offered topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The offered topic could not be saved. Please, try again.'));
        }
        $internalConsultants = $this->OfferedTopics->InternalConsultants->find('list', ['limit' => 200]);
        $students = $this->OfferedTopics->Students->find('list', ['limit' => 200]);
        $this->set(compact('offeredTopic', 'internalConsultants', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Offered Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $offeredTopic = $this->OfferedTopics->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $offeredTopic = $this->OfferedTopics->patchEntity($offeredTopic, $this->request->getData());
            if ($this->OfferedTopics->save($offeredTopic)) {
                $this->Flash->success(__('The offered topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The offered topic could not be saved. Please, try again.'));
        }
        $internalConsultants = $this->OfferedTopics->InternalConsultants->find('list', ['limit' => 200]);
        $students = $this->OfferedTopics->Students->find('list', ['limit' => 200]);
        $this->set(compact('offeredTopic', 'internalConsultants', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Offered Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $offeredTopic = $this->OfferedTopics->get($id);
        if ($this->OfferedTopics->delete($offeredTopic)) {
            $this->Flash->success(__('The offered topic has been deleted.'));
        } else {
            $this->Flash->error(__('The offered topic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
