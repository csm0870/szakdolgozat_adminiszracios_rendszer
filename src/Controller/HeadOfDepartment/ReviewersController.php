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
        if($this->getRequest()->is('post')){
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
     * Dolgozat bírálatra küldése
     * 
     * @param type $thesis_topic_id
     */
    public function sendToReview($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviewers->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                             'contain' => ['Reviews' => ['Reviewers' => ['Users']]]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('Nem létező szakdolgozat/diplomamunka.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 22){ //Nem "Bírálatra vár" státuszban van
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('A dolgozat nem bírálatra küldésre vár.');
            $ok = false;
        }elseif(!$thesisTopic->has('review') || !$thesisTopic->review->has('reviewer')){ //Nincs kijelölve bíráló
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('A dolgozathoz nincs kijelölve bíráló.');
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
            //Itt valamiért még létrehozza a usert
            if(!$thesisTopic->review->reviewer->has('user')){ //Ha még nincs hozzárendelve user
                //Usergenerálás
                //Névgenerálás
                
                //Ékezetes karakrerek eltávolítására
                $trans = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD);
                
                $name = $temp_name = empty($thesisTopic->review->reviewer->name) ? 'biralo' : strtolower($trans->transliterate(str_replace(' ', '', $thesisTopic->review->reviewer->name)));
                //Email generálás
                for($i = 0; $this->Reviewers->Users->exists(['email' => $temp_name . '@biralo.hu']) === true ;$i++){
                    $temp_name = $name . $i;
                }
                $name = $temp_name;
                
                //Jelszógenerálás
                $password_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789?#&$";
                $pw = "";
                for($i = 0; $i < 8; $i++){
                    $ch = substr($password_characters, random_int(0, strlen($password_characters) - 1),1);
                    $pw.= random_int(1, 2) == 1 ? $ch : strtolower($ch);
                }

                $reviewer_user = $this->Reviewers->Users->newEntity();
                $reviewer_user->email = $name . "@biralo.hu";
                $reviewer_user->password = $pw;
                $reviewer_user->group_id = 7;
            }
            
            
            if($thesisTopic->review->reviewer->has('user') || $this->Reviewers->Users->save($reviewer_user)){
                $saved_ok = true;
                if(!$thesisTopic->review->reviewer->has('user')){
                    $thesisTopic->review->reviewer->user_id = $reviewer_user->id;
                    //Nem menti a jelszót :D
                    $raw_password = $this->Reviewers->Users->RawPasswords->newEntity();
                    
                    $raw_password->password = $pw;
                    $raw_password->user_id = $reviewer_user->id;
                    if(!$this->Reviewers->save($thesisTopic->review->reviewer) || !$this->Reviewers->Users->RawPasswords->save($raw_password)){
                        $saved_ok = false;
                    }
                }
                
                if($saved_ok === true){ \Cake\Log\Log::write('error', 'topic_status');
                    $thesisTopic->thesis_topic_status_id = 23; //Bírálat alatt
                    if($this->Reviewers->Reviews->ThesisTopics->save($thesisTopic)){
                        $this->Flash->success(__('Bírálatra küldve.'));
                    }else{
                        $saved = false;
                        $error_ajax = __('Dolgozat állapotának megváltoztása sikertelen. Próbálja újra!');

                        $errors = $reviewer_user->getErrors();
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
                }else{
                    $saved = false;
                    $error_ajax = __('Bírálói felhasználó mentése sikertelen. Próbálja újra!');
                }
            }else{
                $saved = false;
                $error_ajax = __('Bírálói felhasználó létrehozása sikertelen. Próbálja újra!');
                
                $errors = $reviewer_user->getErrors();
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
        
        $this->set(compact('saved', 'error_ajax', 'ok', 'error_msg', 'thesisTopic'));
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
