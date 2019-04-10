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
     * Konzultációs alkalmak listája
     *
     * @return \Cake\Http\Response|void
     */
    public function index($consultation_id = null){
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        if(empty($consultation)){
            $this->Flash->error(__('Konzultációs csoport nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A konzultációs csoportotokat nem láthatja.') . ' ' . __('A téma, amelyhez tartozik nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A konzultációs csoportotokat nem láthatja.') . ' ' . __('A témának, amelyhez tartozik, nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            $this->Flash->error(__('A konzultációs csoportotokat nem láthatja.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy eléreje az alkalmakat.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($consultation->accepted !== null){ //Már véglegesített
            $this->Flash->error(__('A konzultációs alkalmakat nem láthatja.') . ' ' . __('A konzultációs csoport, amihez tartozik már véglegesített. Csak a PDF-ben tekinthető(ek) meg.'));
            return $this->redirect(['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id]);
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $this->Flash->error(__('A konzultációs alkalmakat nem láthatja.') . ' ' . __('A konzultációs csoport, amihez tartozik régebbi dolgozathoz tartozik.'));
            return $this->redirect(['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id]);
        }
        
        $consultationOccasions = $this->ConsultationOccasions->find('all', ['conditions' => ['consultation_id' => $consultation->id], 'order' => ['date' => 'DESC']]);
        $this->set(compact('consultationOccasions', 'consultation', 'thesisTopic'));
    }
    
    /**
     * Konzultációs alkalom hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($consultation_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $error_msg = '';
        $ok = true;
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultation_id]])->first();
        
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.');
            $ok = false;
        }elseif($consultation->accepted !== null){ //Már véglegesített
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik már véglegesített.');
            $ok = false;
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A konzultációs csoport, amihez tartozik régebbi dolgozathoz tartozik.');
            $ok = false;
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A téma, amelyhez tartozik nem létezik.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A témának, amelyhez tartozik nem Ön a belső konzulense.');
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            $error_msg = __('A konzultációs alkalom nem adható hozzá.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy hozzáadhatna konzultácós alkalmat.');
            $ok = false;
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        
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
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Konzultációs alkalom szerkesztése
     *
     * @param string|null $id Consultation Occasion id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $error_msg = '';
        $ok = true;
        
        $consultationOccasion = $this->ConsultationOccasions->find('all', ['conditions' => ['id' => $id]])->first();
        if(empty($consultationOccasion)){ //Ha nem létezik a konzultációs alkalom
            $error_msg = __('A kért konzultációs alkalom nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultationOccasion->consultation_id]])->first();
        
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.');
            $ok = false;
        }elseif($consultation->accepted !== null){ //Már véglegesített
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A konzultációs csoport, amihez tartozik már véglegesített.');
            $ok = false;
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A konzultációs csoport, amihez tartozik régebbi dolgozat verzióhoz tartozik.');
            $ok = false;
        }
        
        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A téma, amelyhez tartozik nem létezik.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A témának, amelyhez tartozik nem Ön a belső konzulense.');
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            $error_msg = __('A konzultációs alkalom nem szerkeszthető.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az éllapotban, hogy szerkeszthető lenne a konzultációs alkalom.');
            $ok = false;
        }

        if(!$ok){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        
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
        
        $this->set(compact('consultationOccasion', 'saved', 'error_ajax', 'consultation', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Konzultációs alkalom törlése
     *
     * @param string|null $id Consultation Occasion id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        
        $consultationOccasion = $this->ConsultationOccasions->find('all', ['conditions' => ['id' => $id]])->first();
        if(empty($consultationOccasion)){ //Ha nem létezik a konzultációs alkalom
            $this->Flash->error(__('A kért konzultációs alkalom nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $ok = true;
        $consultation = $this->ConsultationOccasions->Consultations->find('all', ['conditions' => ['id' => $consultationOccasion->consultation_id]])->first();
        if(empty($consultation)){ //Ha nem létezik a konzultációs csoport
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A konzultációs csoport, amihez tartozik nem létezik.'));
            $ok = false;
        }elseif($consultation->accepted !== null){ //Már véglegesített
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A konzultációs csoport, amihez tartozik már véglegesített.'));
            $ok = false;
        }elseif($consultation->current === false){ //Régi szakdolgozathoz tartozik
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A konzultációs csoport, amihez tartozik régebbi dolgozathoz tartozik.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect($this->referer(null, true));
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A téma, amelyhez tartozik nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A témának, amelyhez tartozik nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')])){
            $this->Flash->error(__('A konzultációs alkalom nem törölhető.') . ' ' . __('A téma, amelyhez tartozik, nincs abban az állapotban, hogy törölhető lenne a konzultációs alkalom.'));
            $ok = false;
        }

        if(!$ok) return $this->redirect($this->referer(null, true));
        
        if($this->ConsultationOccasions->delete($consultationOccasion)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index', $consultationOccasion->consultation_id]);
    }
}
