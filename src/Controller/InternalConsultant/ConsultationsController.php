<?php
namespace App\Controller\InternalConsultant;

use App\Controller\AppController;

/**
 * Consultations Controller
 *
 * @property \App\Model\Table\ConsultationsTable $Consultations
 *
 * @method \App\Model\Entity\Consultation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConsultationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                          'thesis_topic_status_id' => 8], //Belső konzulenshez tartozik és elfogadott
                                                                          ])->first();
        
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma konzultációi nem elérhetők. Vagy nem létezik a téma, vagy nem Ön a belső konzulense.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $consultations = $this->Consultations->find('all', ['conditions' => ['thesis_topic_id' => $thesisTopic->id], 'order' => ['created' => 'DESC']]);
        
        $this->set(compact('consultations'));
    }

    /**
     * View method
     *
     * @param string|null $id Consultation id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $consultation = $this->Consultations->get($id, [
            'contain' => ['Theses', 'ConsultationOccasions']
        ]);

        $this->set('consultation', $consultation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $consultation = $this->Consultations->newEntity();
        if ($this->request->is('post')) {
            $consultation = $this->Consultations->patchEntity($consultation, $this->request->getData());
            if ($this->Consultations->save($consultation)) {
                $this->Flash->success(__('The consultation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The consultation could not be saved. Please, try again.'));
        }
        $theses = $this->Consultations->Theses->find('list', ['limit' => 200]);
        $this->set(compact('consultation', 'theses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Consultation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $consultation = $this->Consultations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $consultation = $this->Consultations->patchEntity($consultation, $this->request->getData());
            if ($this->Consultations->save($consultation)) {
                $this->Flash->success(__('The consultation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The consultation could not be saved. Please, try again.'));
        }
        $theses = $this->Consultations->Theses->find('list', ['limit' => 200]);
        $this->set(compact('consultation', 'theses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Consultation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $consultation = $this->Consultations->get($id);
        if ($this->Consultations->delete($consultation)) {
            $this->Flash->success(__('The consultation has been deleted.'));
        } else {
            $this->Flash->error(__('The consultation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
