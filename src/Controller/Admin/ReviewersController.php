<?php
namespace App\Controller\Admin;

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
        if(in_array($this->getRequest()->getParam('action'), ['setReviewerForThesisTopic', 'setReviewerSuggestion'])) $this->viewBuilder()->setLayout(false);
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
     */
    public function add(){
        $reviewer = $this->Reviewers->newEntity();
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)) {
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $reviewer->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        $this->set(compact('reviewer'));
    }

    /**
     * Bíráló adatainak szerkesztése
     *
     * @param string|null $id Bíráló egyedi azonosítója
     */
    public function edit($id = null){
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $id]])->first();
        
        if(empty($reviewer)){ //Nem létezik a bíráló
            $this->Flash->error(__('A bíráló adatai nem szerkeszthetőek.') . ' ' . __('Nem létező bíráló.'));
            return $this->redirect (['action' => 'index']);
        }
        
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)) {
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'edit', $reviewer->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        $this->set(compact('reviewer'));
    }
    
    /**
     * Bíráló részletek
     * 
     * @param type $id Bíráló egyedi azonosítója
     */
    public function details($id = null){
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $id],
                                                   'contain' => ['Users' => ['RawPasswords']]])->first();
        
        if(empty($reviewer)){ //Nem létezik a bíráló
            $this->Flash->error(__('A bíráló részletei nem elérhetők.') . ' ' . __('Nem létező bíráló.'));
            return $this->redirect (['action' => 'index']);
        }
        
        $this->set(compact('reviewer'));
    }
    
    /**
     * Bíráló törlése
     *
     * @param string|null $id Bíráló egyedi azonosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
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
            if($this->Reviewers->delete($reviewer)) $this->Flash->success(__('Törlés sikeres.'));
            else $this->Flash->error(__('Törlés sikertelen.'));
        }else $this->Flash->error(__('A bírálónak még van folyamatban lévő bíráláta. Nem törölheti.'));

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Bíráló személyének javaslata (belső konzulensi művelet)
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function setReviewerSuggestion($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviewers->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálói javaslat tétele nem lehetséges.') . ' ' . __('Nem létező dolgozat.');
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
        if($this->request->is('post')){
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
     * Bíráló személyének javaslata (tanszékvezetői művelet)
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function setReviewerForThesisTopic($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviewers->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                             'contain' => ['Reviews' => ['Reviewers']]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálói kijelölése nem lehetséges.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){ //Nem "Bíráló kijelölésére vár státuszban van" státuszban van
            $error_msg = __('Bírálói kijelölése nem lehetséges.') . ' ' . __('A dolgozat nem a biráló személyének kijelölésére vár.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is('post')){
            $reviewer_id = $this->getRequest()->getData('reviewer_id');
            
            if(empty($reviewer_id)){
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!') . ' ' . __('Bírálót kötelező megadni.');
            }elseif(!$this->Reviewers->exists(['id' => $reviewer_id])){
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!') . ' ' . __('Bíráló nem létezik.');
            }else{
                if(!$thesisTopic->has('review')){ //Ha nem lenne valamiért hozzárendelve
                    $review = $this->Reviewers->Reviews->newEntity();
                    $review->thesis_topic_id = $thesisTopic->id;
                }else{
                    $review = $thesisTopic->review;
                }
                
                $review->reviewer_id = $reviewer_id;
                
                if($this->Reviewers->Reviews->save($review)){
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview'); //Bíráló kijelölve, bírálatra vár
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
        
        $reviewers_list = $this->Reviewers->find('list');
        $reviewers = $this->Reviewers->find('all');
        $this->set(compact('reviewers', 'reviewers_list', 'saved', 'error_ajax', 'ok', 'error_msg', 'thesisTopic'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
