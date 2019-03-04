<?php
namespace App\Controller\HeadOfDepartment;

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
        if($this->getRequest()->getParam('action') != 'index') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Bíráló személyének javaslata
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
            $error_msg = __('Bírálói kijelölése nem lehetséges.') . ' ' . __('Nem létező szakdolgozat/diplomamunka.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 21){ //Nem "Bíráló kijelölésére vár státuszban van" státuszban van
            $error_msg = __('Bírálói kijelölése nem lehetséges.') . ' ' . __('A szakdolgozat/diplomamunka nem a biráló személyének kijelölésére vár.');
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
                if(!$thesisTopic->has('review')){ //Ha nem lenne hozzárendelve review
                    $review = $this->Reviewers->Reviews->newEntity();
                    $review->thesis_topic_id = $thesisTopic->id;
                }else $review = $thesisTopic->review;
                
                $review->reviewer_id = $reviewer_id;
                
                if($this->Reviewers->Reviews->save($review)){
                    $thesisTopic->thesis_topic_status_id = 22; //Bíráló kijelölve, bírálatra vár
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
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($reviewer)){
            $this->Flash->success(__('Helytelen aznosító.') . __('A bíráló nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->Reviewers->delete($reviewer)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
