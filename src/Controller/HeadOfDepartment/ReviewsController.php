<?php
namespace App\Controller\HeadOfDepartment;

use App\Controller\AppController;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 *
 * @method \App\Model\Entity\Review[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['sendToReview', 'checkConfidentialityContract', 'acceptReview'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Dolgozat bírálatra küldése
     * 
     * @param type $thesis_topic_id
     */
    public function sendToReview($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                             'contain' => ['Reviews' => ['Reviewers' => ['Users']]]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('Nem létező dolgozat.');
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
                for($i = 0; $this->Reviews->Reviewers->Users->exists(['email' => $temp_name . '@biralo.hu']) === true ;$i++){
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

                $reviewer_user = $this->Reviews->Reviewers->Users->newEntity();
                $reviewer_user->email = $name . "@biralo.hu";
                $reviewer_user->password = $pw;
                $reviewer_user->group_id = 7;
            }
            
            
            if($thesisTopic->review->reviewer->has('user') || $this->Reviewers->Users->save($reviewer_user)){
                $saved_ok = true;
                if(!$thesisTopic->review->reviewer->has('user')){
                    $thesisTopic->review->reviewer->user_id = $reviewer_user->id;
                    //Nem menti a jelszót :D
                    $raw_password = $this->Reviews->Reviewers->Users->RawPasswords->newEntity();
                    
                    $raw_password->password = $pw;
                    $raw_password->user_id = $reviewer_user->id;
                    if(!$this->Reviews->Reviewers->save($thesisTopic->review->reviewer) || !$this->Reviewers->Users->RawPasswords->save($raw_password)){
                        $saved_ok = false;
                    }
                }
                
                if($saved_ok === true){
                    $thesisTopic->thesis_topic_status_id = 23; //Bírálat alatt
                    if($this->Reviews->ThesisTopics->save($thesisTopic)){
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
     * Bírálat ellenőrzése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function checkReview($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews' => ['Reviewers', 'Questions']]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23, 24, 25])){ //Nem "Bírálat alatt", vagy "Bírálva státuszban van" státuszban van
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozat még nem lett bírálva, vagy nem bírálat alatt van.'));
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozathoz nem tartozik bírálat.'));
            $ok = false;
        }elseif($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Még nincs elfogadva a titoktartási szerződés
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('Még nincs elfogadott titoktartási szerződés a bírálótól.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->review->review_status, [4, 5, 6])){ //Még nincs véglegesített bírálat
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A bírálat még nem született meg.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Bíráló személyének javaslata
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function acceptReview($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews']])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 23){ //Nem "Bírálat alatt" státuszban van
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('A dolgozat nem birálatt alatti állapotban van.');
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('A dolgozathoz nem tartozik bírálat.');
            $ok = false;
        }elseif($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Még nincs elfogadott titoktartási szerződés
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('Még nincs elfogadott titoktartási szerződés a bírálótól.');
            $ok = false;
        }elseif($thesisTopic->review->review_status == 5){ //El van utasítva bírálat
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('A bírálat már el van utasítva..');
            $ok = false;
        }elseif($thesisTopic->review->review_status != 4){ //Még nincs véglegesített bírálat
            $error_msg = __('A bírálatról nem dönthet.') . ' ' . __('A bírálat még nem született meg..');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $accepted = $this->getRequest()->getData('accepted');
            
            if($accepted === null || !in_array($accepted, [0, 1])){
                $thesisTopic->review->setError('custom', __('A döntésnek "0" (nem) vagy "1" (igen) értéket kell felvennie!'));
            }else{
                if($accepted == 0){
                    $thesisTopic->review->review_status = 5; //Eltuasítva
                    $thesisTopic->review->cause_of_rejecting_review = $this->getRequest()->getData('cause_of_rejecting_review');
                }else{
                    $thesisTopic->review->review_status = 6; //Elfogadva
                    $thesisTopic->thesis_topic_status_id = 24; //Bírálva
                }
            }
                
            if($this->Reviews->save($thesisTopic->review)){
                if($accepted == 1){
                    if($this->Reviews->ThesisTopics->save($thesisTopic)){
                        $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
                    }else{
                        $thesisTopic->review->review_status = 4;
                        $this->Reviews->save($thesisTopic->review);
                        $saved = false;
                        $error_ajax = __('Elfogadás sikertelen.') . ' ' . __('Próbálja újra!');
                    }
                }else
                    $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!');

                $errors = $thesisTopic->review->getErrors();
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
     * Feltöltött bírálati lap letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getReviewDoc($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews']])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [23, 24, 25])){ //Nem "Bírálat alatt", vagy "Bírálva státuszban van" státuszban van
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A dolgozat még nem lett bírálva, vagy nem bírálatt alatt van.'));
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A dolgozathoz nem tartozik bírálat.'));
            $ok = false;
        }elseif($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Még nincs elfogadva a titoktartási szerződés
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Még nincs elfogadott titoktartási szerződés a bírálótól.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->review->review_status, [4, 5, 6])){ //Még nincs véglegesített bírálat
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A bírálat még nem született meg.'));
            $ok = false;
        }elseif(empty($thesisTopic->review->review_doc)){ //Ha nincs bírálati lap feltöltve
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Nincs feltöltve bírálati lap.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'review_docs' . DS . $thesisTopic->review->review_doc,
                                                   ['download' => true, 'name' => $thesisTopic->review->review_doc]);

        return $response;
    }
    
    /**
     * Bíráló személyének javaslata
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function checkConfidentialityContract($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews']])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 23){ //Nem "Bírálat alatt" státuszban van
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('A dolgozat nem birálatt alatti állapotban van.');
            $ok = false;
        }elseif($thesisTopic->confidential !== true){ //Nem titkos a dolgozat
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . ' ' . __('A dolgozat nem titkos.');
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('A dolgozathoz nem tartozik bírálat.');
            $ok = false;
        }elseif($thesisTopic->review->confidentiality_contract_status == 4){ //Már el van fogadva a titoktartási szerződés
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('A titoktartási szerződés már el van fogadva.');
            $ok = false;
        }elseif($thesisTopic->review->confidentiality_contract_status == 3){ //El van utasítva a titoktartási szerződés
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('A titoktartási szerződés el van utasítva.');
            $ok = false;
        }elseif($thesisTopic->review->confidentiality_contract_status == 1 || $thesisTopic->review->confidentiality_contract_status == null){ //Még nincs feltöltve a titoktartási szerződés
            $error_msg = __('A feltöltött titoktartási szerződésről nem dönthet.') . ' ' . __('A titoktartási szerződés még nincs feltöltve.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $accepted = $this->getRequest()->getData('accepted');
            
            if($accepted === null || !in_array($accepted, [0, 1])){
                $thesisTopic->review->setError('custom', __('A döntésnek "0" (nem) vagy "1" (igen) értéket kell felvennie!'));
            }else{
                if($accepted == 0){
                    $thesisTopic->review->confidentiality_contract_status = 3; //Eltuasítva
                    $thesisTopic->review->cause_of_rejecting_confidentiality_contract = $this->getRequest()->getData('cause_of_rejecting_confidentiality_contract');
                }else $thesisTopic->review->confidentiality_contract_status = 4; //Elfogadva
            }
                
            if($this->Reviews->save($thesisTopic->review)){
                $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!');

                $errors = $thesisTopic->review->getErrors();
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
     * Feltöltött titoktartási szerződés letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getUploadedConfidentialityContract($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews']])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->confidential !== true){ //Nem titkos a dolgozat
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . ' ' . __('A dolgozat nem titkos.'));
            $ok = false;
        }elseif(!$thesisTopic->has('review') || empty($thesisTopic->review->confidentiality_contract)){ //Ha nincs titoktartási szerződés feltöltve
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nincs feltöltve titoktartási szerződés.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'confidentiality_contracts' . DS . $thesisTopic->review->confidentiality_contract,
                                                   ['download' => true, 'name' => $thesisTopic->review->confidentiality_contract]);

        return $response;
    }
}
