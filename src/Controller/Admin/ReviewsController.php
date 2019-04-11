<?php
namespace App\Controller\Admin;

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
        if(in_array($this->getRequest()->getParam('action'), ['sendToReview', 'checkConfidentialityContract', 'acceptReview', 'sendToReviewAgain'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Dolgozat bírálatra küldése (tanszékvezetői művelet)
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
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview')){ //Nem "Bírálatra vár" státuszban van
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
            
            if($thesisTopic->review->reviewer->has('user') || $this->Reviews->Reviewers->Users->save($reviewer_user)){
                $saved_ok = true;
                if(!$thesisTopic->review->reviewer->has('user')){
                    $thesisTopic->review->reviewer->user_id = $reviewer_user->id;
                    //Nem menti a jelszót :D
                    $raw_password = $this->Reviews->Reviewers->Users->RawPasswords->newEntity();
                    
                    $raw_password->password = $pw;
                    $raw_password->user_id = $reviewer_user->id;
                    if(!$this->Reviews->Reviewers->save($thesisTopic->review->reviewer) || !$this->Reviews->Reviewers->Users->RawPasswords->save($raw_password)){
                        $saved_ok = false;
                    }
                }
                
                if($saved_ok === true){
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'); //Bírálat alatt
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
     * Dolgozat újboli bírálatra küldése másik bíráló választási lehetőséggel
     * 
     * @param type $thesis_topic_id
     */
    public function sendToReviewAgain($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                  'contain' => ['Reviews']])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $error_msg = __('Bírálatra küldés nem lehetséges.') . ' ' . __('A dolgozat nem bírálat alatt van.');
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
            }elseif(!$this->Reviews->Reviewers->exists(['id' => $reviewer_id])){
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!') . ' ' . __('Bíráló nem létezik.');
            }else{
                $review = $this->Reviews->newEntity();
                $review->thesis_topic_id = $thesisTopic->id;
                $review->reviewer_id = $reviewer_id;
                
                $current_review = $this->Reviews->find('all', ['conditions' => ['Reviews.thesis_topic_id' => $thesisTopic->id]])->first();
                
                if(!empty($current_review) && !$this->Reviews->delete($current_review)){
                    $saved = false;
                    $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                }else{
                    if($this->Reviews->save($review)){
                        $reviewer = $this->Reviews->Reviewers->get($reviewer_id, ['contain' => ['Users']]);
                        if(!$reviewer->has('user')){ //Ha még nincs hozzárendelve user
                            //Usergenerálás
                            //Névgenerálás

                            //Ékezetes karakrerek eltávolítására
                            $trans = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD);

                            $name = $temp_name = empty($reviewer->name) ? 'biralo' : strtolower($trans->transliterate(str_replace(' ', '', $reviewer->name)));
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

                        if($reviewer->has('user') || $this->Reviews->Reviewers->Users->save($reviewer_user)){
                            $saved_ok = true;
                            if(!$reviewer->has('user')){ //Ha eddig nem volt userje, akkor most lett, ezért el kell menteni a jelszót is
                                $reviewer->user_id = $reviewer_user->id;
                                
                                $raw_password = $this->Reviews->Reviewers->Users->RawPasswords->newEntity();
                                $raw_password->password = $pw;
                                $raw_password->user_id = $reviewer_user->id;
                                if(!$this->Reviews->Reviewers->save($reviewer) || !$this->Reviews->Reviewers->Users->RawPasswords->save($raw_password)){
                                    $saved_ok = false;
                                }
                            }

                            if($saved_ok === true){
                                $this->Flash->success(__('Bírálatra küldve.'));
                                
                                //Értesítés a megfelelő usereknek
                                $this->loadModel('Notifications');
                                
                                $this->loadModel('Students');
                                $student = $this->Students->find('all', ['conditions' => ['Students.id' => $thesisTopic->student_id],
                                                                         'contain' => ['Users']])->first();
                                if(!empty($student) && $student->has('user')){
                                    $notification = $this->Notifications->newEntity();
                                    $notification->user_id = $student->user_id;
                                    $notification->unread = true;
                                    $notification->subject = 'A leadott ' . ($thesisTopic->is_thesis === true ? 'szakdolgozata' : 'diplomamunkája') . ' újra bírálatra lett küldve.';
                                    $notification->message = 'A ' . h($thesisTopic->title) . ' című ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálatra lett küldve. A bírálat után megtekintheti a bírálatot.' . '<br/>' .
                                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';

                                    $this->Notifications->save($notification);
                                }
                                
                                $this->loadModel('InternalConsultants');
                                $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $thesisTopic->internal_consultant_id],
                                                                                               'contain' => ['Users']])->first();
                                if(!empty($internalConsultant) && $internalConsultant->has('user')){
                                    $notification = $this->Notifications->newEntity();
                                    $notification->user_id = $internalConsultant->user_id;
                                    $notification->unread = true;
                                    $notification->subject = 'Egy Önhöz tartozó ' . ($thesisTopic->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . 'újra bírálatra lett küldve.';
                                    $notification->message = 'A téma címe: ' . h($thesisTopic->title) . '<br/>' .
                                                             'Hallgató: ' . (empty($student) ? '' : (h($student->name) . ' (' . h($student->neptun) . ')')) . '<br/>' .
                                                             'A bírálat után a bírálatot megtekintheti.' . '<br/>' .
                                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                                    $this->Notifications->save($notification);
                                }

                                $this->loadModel('Languages');
                                $language = $this->Languages->find('all', ['conditions' => ['Languages.id' => $thesisTopic->language_id]])->first();

                                $notification = $this->Notifications->newEntity();
                                $notification->user_id = $reviewer->has('user') ? $reviewer->user->id : $reviewer_user->id;
                                $notification->unread = true;
                                $notification->subject = 'Egy ' . ($thesisTopic->is_thesis === true ? 'szakdolgozathoz' : 'diplomamunkáhpz') . ' Önt jelölték ki bírálónak.';
                                $notification->message = 'Dolgozat címe: ' . h($thesisTopic->title) . '<br/>' .
                                                         'Titkos: ' . ($thesisTopic->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                                         (!empty($language) ? 'Nyelv: ' . h($language->name) . '<br/>' : '') .
                                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'reviewer'], true) . '">' . 'Részletek megtekintése' . '</a>';

                                $this->Notifications->save($notification);
                                
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
        
        $reviewers_list = $this->Reviews->Reviewers->find('list');
        $reviewers = $this->Reviews->Reviewers->find('all');
        $this->set(compact('reviewers', 'reviewers_list', 'saved', 'error_ajax', 'ok', 'error_msg', 'thesisTopic'));
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
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){ //Nem "Bírálat alatt", vagy "Bírálva státuszban van" státuszban van
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
     * Bíráló személyének javaslata (tanszékvezetői művelet)
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
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
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
                    $thesisTopic->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'); //Bírálva
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
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')])){
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
     * Titoktartási szerződés ellenőrzése (tanszékvezetői művelet)
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
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
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
    
    /**
     * Dolgozat bírálata (bírálói művelet)
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function review($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){
            $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('A dolgozat nincs abban az állapotban, hogy a bírálható legyen.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews' => ['Questions']])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha a titoktartási szerződés még nincs elfogadva
                $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('Először a feltöltött titoktartási szerződés el kell fogadnia a tanszékvezetőnek.'));
                $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $is_finalize = $this->getRequest()->getData('is_finalize');
            //Véglegesítésről van-e szó
            if(!empty($is_finalize) && $is_finalize == 1){
                if($thesisTopic->review->review_status != 1){ //Ha még nincs mentvebírálat
                    $this->Flash->error(__('A bírálat nem véglegesíthető.') . ' ' . __('Először meg kell tenni a bírálatot.'));
                    $ok = false;
                }
            }else{
                if(in_array($thesisTopic->review->review_status, [2, 3, 4])){ //Már véglegesítve van a bírálat
                    $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('A bírálat már véglegesítve van.'));
                    $ok = false;
                }elseif($thesisTopic->review->review_status == 6){ //Már el van fogadva van a bírálat
                    $this->Flash->error(__('A bírálat nem tehető meg.') . ' ' . __('A bírálat már el van fogadva.'));
                    $ok = false;
                }
            }
            if(!$ok) return $this->redirect(['action' => 'review', $thesisTopic->id]);
            
            $thesisTopic->review = $this->Reviews->patchEntity($thesisTopic->review, $this->getRequest()->getData());
            
            if($this->getRequest()->getData('structure_and_style_point') == null){
                $thesisTopic->review->setError('structure_and_style_point', __('Pont megadása kötelező.'));
            }
            
            if(empty($this->getRequest()->getData('cause_of_structure_and_style_point'))){
                $thesisTopic->review->setError('cause_of_structure_and_style_point', __('Indoklás megadása kötelező.'));
            }
            
            if($this->getRequest()->getData('processing_literature_point') == null){
                $thesisTopic->review->setError('processing_literature_point', __('Pont megadása kötelező.'));
            }
            
            if(empty($this->getRequest()->getData('cause_of_processing_literature_point'))){
                $thesisTopic->review->setError('cause_of_processing_literature_point', __('Indoklás megadása kötelező.'));
            }
            
            if($this->getRequest()->getData('writing_up_the_topic_point') == null){
                $thesisTopic->review->setError('writing_up_the_topic_point', __('Pont megadása kötelező.'));
            }
            
            if(empty($this->getRequest()->getData('cause_of_writing_up_the_topic_point'))){
                $thesisTopic->review->setError('cause_of_writing_up_the_topic_point', __('Indoklás megadása kötelező.'));
            }
            
            if($this->getRequest()->getData('practical_applicability_point') == null){
                $thesisTopic->review->setError('practical_applicability_point', __('Pont megadása kötelező.'));
            }
            
            if(empty($this->getRequest()->getData('cause_of_practical_applicability_point'))){
                $thesisTopic->review->setError('cause_of_practical_applicability_point', __('Indoklás megadása kötelező.'));
            }
            
            if(empty($this->getRequest()->getData('general_comments'))){
                $thesisTopic->review->setError('general_comments', __('Megjegyzés megadása kötelező.'));
            }
            
            //Azok a mezők kikapsolása, amit itt nem menthet a biztonság kedvéért
            unset($thesisTopic->review->confidentiality_contract);
            unset($thesisTopic->review->confidentiality_contract_status);
            unset($thesisTopic->review->cause_of_rejecting_confidentiality_contract);
            unset($thesisTopic->review->cause_of_rejecting_review);
            unset($thesisTopic->review->review_status);
            unset($thesisTopic->review->review_doc);
                        
            //Kérdések ellenőrzése, hogy legalább 3 van-e
            $questions = $this->getRequest()->getData('questions');
            
            //Azon kérdések azonosítójai, amelyek a kérésben vannak
            $question_ids_in_request = [];
            if(!empty($questions)){
                foreach($questions as $question){
                    if(!empty($question['question'])){
                        if(isset($question['id'])){
                            $question_ids_in_request[] = $question['id'];
                        }
                    }
                }
            }
            
            //Az eddig mentett kérdések azonosítójai
            $current_question_ids = [];
            foreach($thesisTopic->review->questions as $question){
                $current_question_ids[] = $question->id;
            }

            //Azon ID-k, amelyek a kérésbe nincsenek, de léteznek, ezek törölve lesznek
            $question_ids_to_delete = array_diff($current_question_ids, $question_ids_in_request);
            foreach($question_ids_to_delete as $id){
                $question = $this->Reviews->Questions->find('all', ['conditions' => ['id' => $id, 'review_id' => $thesisTopic->review->id]])->first();
                if(!empty($question)) $this->Reviews->Questions->delete($question);
            }
            
            if(!empty($is_finalize) && $is_finalize == 1) $thesisTopic->review->review_status = 2; //Bírálat véglegesítve
            else $thesisTopic->review->review_status = 1; //Bírálat feltöltve
            
            if($this->Reviews->save($thesisTopic->review)){
                $this->Flash->success(__('Mentés sikeres.'));
                return $this->redirect(['action' => 'review', $thesisTopic->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        
        $this->loadModel('Documents');
        //Szakdolgozat bírálati útmutató
        $thesis_review_guide = $this->Documents->find('all', ['conditions' => ['id' => 1]])->first();
        //Diplomamunka bírálati útmutató
        $diploma_review_guide = $this->Documents->find('all', ['conditions' => ['id' => 2]])->first();

        $this->set(compact('thesisTopic', 'thesis_review_guide', 'diploma_review_guide'));
    }
}
