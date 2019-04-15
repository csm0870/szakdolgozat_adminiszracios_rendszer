<?php
namespace App\Controller\InternalConsultant;

use App\Controller\AppController;

/**
 * Reviewers Controller
 *
 * @property \App\Model\Table\ReviewersTable $Reviewers
 *
 * @method \App\Model\Entity\Reviewer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit', 'setReviewerSuggestion'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Bírálók listája
     */
    public function index(){
        $reviewers = $this->Reviewers->find('all');
        $this->set(compact('reviewers'));
    }

    /**
     * Bíráló hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $saved = true;
        $error_ajax = "";
        $reviewer = $this->Reviewers->newEntity();
        if ($this->getRequest()->is('post')) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $reviewer->getErrors();
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
        
        $this->set(compact('reviewer', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reviewer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $ok = true;
        $error_msg = '';
        
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($reviewer)){
            $ok = false;
            $error_msg = __('Helytelen aznosító.') . __('A bíráló nem létezik.');
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $reviewer->getErrors();
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
        
        $this->set(compact('reviewer', 'saved', 'error_ajax', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Bíráló személyének javaslata
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function setReviewerSuggestion($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        $thesisTopic = $this->Reviewers->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálói javaslat tétele nem lehetséges.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('Bírálói javaslat tétele nem lehetséges.') . ' ' . __('A dolgozatnak nem Ön a belső konzulense.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')){ //Nem "Bíráló kijelölésére vár státuszban van" státuszban van
            $error_msg = __('Bírálói javaslat tétele nem lehetséges.') . ' ' . __('A dolgozat nem a belső konzulens általi biráló személyének kijelölésére vár.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if ($this->request->is('post')){
            $reviewer_id = $this->getRequest()->getData('reviewer_id');
            
            if(empty($reviewer_id)){
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!') . ' ' . __('Bírálót kötelező megadni.');
            }elseif(!$this->Reviewers->exists(['id' => $reviewer_id])){
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!') . ' ' . __('Bíráló nem létezik.');
            }else{
                $review = $this->Reviewers->Reviews->newEntity();
                $review->thesis_topic_id = $thesisTopic->id;
                $review->reviewer_id = $reviewer_id;
                
                $current_review = $this->Reviewers->Reviews->find('all', ['conditions' => ['Reviews.thesis_topic_id' => $thesisTopic->id]])->first();
                
                if(!empty($current_review) && !$this->Reviewers->Reviews->delete($current_review)){
                    $saved = false;
                    $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                }else{
                    if($this->Reviewers->Reviews->save($review)){
                        $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment'); //Bíráló kijelölve
                        if($this->Reviewers->Reviews->ThesisTopics->save($thesisTopic)){
                            $this->Flash->success(__('Mentés sikeres.'));
                        }else{
                            $saved = false;
                            $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                        }
                    }else{
                        $saved = false;
                        $error_ajax = __('Mentés sikertelen. Próbálja újra!');

                        $errors = $review->getErrors();
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
            }
        }
        
        $reviewers_list = $this->Reviewers->find('list');
        $reviewers = $this->Reviewers->find('all');
        $this->set(compact('reviewers', 'reviewers_list', 'saved', 'error_ajax', 'ok', 'error_msg', 'thesisTopic'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Bíráló törlése
     *
     * @param string|null $id Bíráló aznosítója
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $id],
                                                   'contain' => ['Reviews']])->first();
        
        if(empty($reviewer)){
            $this->Flash->success(__('Helytelen aznosító.') . __('A bíráló nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $can_be_deleted = true;
        foreach($reviewer->reviews as $review){
            if($review->review_status != 6) $can_be_deleted = false;
        }
        
        if($can_be_deleted === true){ //Ha nincs folyamatban lévő bírálata
            if($this->Reviewers->delete($reviewer)){
                $this->Flash->success(__('Törlés sikeres.'));
            }else{
                $this->Flash->error(__('Törlés sikertelen.'));
            }
        }else $this->Flash->error(__('A bírálónak még van folyamatban lévő bíráláta. Nem törölheti.'));

        return $this->redirect(['action' => 'index']);
    }
}
