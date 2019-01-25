<?php
namespace App\Controller\InternalConsultant;

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

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') != 'index') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($consultation_id = null)
    {
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        if(empty($consultation)){
            $this->Flash->error(__('Konzultációs csoport nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                          'thesis_topic_status_id' => 8], //Belső konzulenshez tartozik és elfogadott
                                                                          ])->first();
        
        if(empty($thesisTopic)){
            $this->Flash->error(__('A téma, amelynél a konzultácoós alkalmakat szeretné megtekinteni, nem elérhető. Vagy nem létezik a téma, vagy nem Ön a belső konzulense.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $consultationOccasions = $this->ConsultationOccasions->find('all', ['conditions' => ['consultation_id' => $consultation->id], 'order' => ['date' => 'DESC']]);
        
        $this->set(compact('consultationOccasions', 'consultation'));
    }
    
    /**
     * Konzultációs alkalmak hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($consultation_id = null)
    {
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $saved = true;
        $error_ajax = "";
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        if(empty($consultation)) throw new \Cake\Core\Exception\Exception(__('Konzultációs csoport nem létezik.'));
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                          'thesis_topic_status_id' => 8], //Belső konzulenshez tartozik és elfogadott
                                                                          ])->first();
        
        if(empty($thesisTopic)) throw new \Cake\Core\Exception\Exception(__('A téma, amelynél a konzultácoós alkalmakat szeretné megtekinteni, nem elérhető. Vagy nem létezik a téma, vagy nem Ön a belső konzulense.'));
        
        $consultationOccasion = $this->ConsultationOccasions->newEntity();
        $consultationOccasion->consultation_id = $consultation->id;
        if ($this->request->is('post')) {
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->request->getData());
            if($this->ConsultationOccasions->save($consultationOccasion)) {
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $consultationOccasion->getErrors();
                if(!empty($errors)){
                    foreach($errors as $error){
                        if(is_array($error)){
                            foreach($error as $err){
                                $error_ajax.= '<br/>' . $err;
                            }
                        }else{
                            $error_ajax.= '<br/>' . $error;
                        }
                    }
                }
            }
        }
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation'));
        $this->set('_serialize', ['saved', 'error_ajax']);
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
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $saved = true;
        $error_ajax = "";
        
        $consultationOccasion = $this->ConsultationOccasions->find('all', ['conditions' => ['id' => $id]])->first();
        if(empty($consultationOccasion)) throw new \Cake\Core\Exception\Exception(__('Konzultációs alkalom nem létezik.'));
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultationOccasion->consultation_id]])->first();
        if(empty($consultation)) throw new \Cake\Core\Exception\Exception(__('Konzultációs csoport nem létezik.'));
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : '',
                                                                          'thesis_topic_status_id' => 8], //Belső konzulenshez tartozik és elfogadott
                                                                          ])->first();
        if(empty($thesisTopic)) throw new \Cake\Core\Exception\Exception(__('A téma, amelynél a konzultácoós alkalmakat szeretné megtekinteni, nem elérhető. Vagy nem létezik a téma, vagy nem Ön a belső konzulense.'));
        
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $consultationOccasion = $this->ConsultationOccasions->patchEntity($consultationOccasion, $this->request->getData());
            if($this->ConsultationOccasions->save($consultationOccasion)) {
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $consultationOccasion->getErrors();
                if(!empty($errors)){
                    foreach($errors as $error){
                        if(is_array($error)){
                            foreach($error as $err){
                                $error_ajax.= '<br/>' . $err;
                            }
                        }else{
                            $error_ajax.= '<br/>' . $error;
                        }
                    }
                }
            }
        }
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation'));
        $this->set('_serialize', ['saved', 'error_ajax']);
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
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        }

        return $this->redirect(['action' => 'index', $consultationOccasion->consultation_id]);
    }
}
