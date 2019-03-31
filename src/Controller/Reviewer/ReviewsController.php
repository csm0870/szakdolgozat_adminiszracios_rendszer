<?php
namespace App\Controller\Reviewer;

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
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['uploadConfidentialityContract', 'uploadReviewDoc'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Dolgozat bírálata
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function review($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
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
            foreach($questions as $question){
                if(!empty($question['question'])){
                    if(isset($question['id'])){
                        $question_ids_in_request[] = $question['id'];
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
    
    /**
     * Bírálat véglegesítése
     * 
     * @param type $thesis_topic_id
     */
    /*public function finalizeReview($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A bírálat nem véglegesíthető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A bírálat nem véglegesíthető.') . ' ' . __('A dolgozat nincs abban az állapotban, hogy a bírálat véglegesíthető lenne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A bírálat nem véglegesíthető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha a titoktartási szerződés még nincs elfogadva
            $this->Flash->error(__('A bírálat nem véglegesíthető.') . ' ' . __('Először a feltöltött titoktartási szerződés el kell fogadnia a tanszékvezetőnek.'));
            $ok = false;
        }else
        
        if(!$ok) return $this->redirect(['action' => 'review', $thesisTopic->id]);
        
        $thesisTopic->review->review_status = 2; //Bírálat véglegesítve
        if($this->Reviews->save($thesisTopic->review)) $this->Flash->success(__('Mentés sikeres.'));
        else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        
        return $this->redirect(['action' => 'review', $thesisTopic->id]);
    }*/
    
    /**
     * Bírálati lap feltöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     * @return type
     */
    public function uploadReviewDoc($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');       
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A bírálati lap nem tölthető fel.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" állapotban van
            $error_msg = __('A bírálati lap nem tölthető fel.') . ' ' . ' ' . __('A dolgozat nem bírálható állapotban van.');
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $error_msg = __('A bírálati lap nem tölthető fel.') . ' ' . __('A dolgozatnak nem Ön a bírálója.');
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha titkos és nincs elfogava a titoktartási szerződés
            $error_msg = __('A bírálati lap nem tölthető fel.') . ' ' . __('A titoktartási szerződés még nincs elfogadva.');
            $ok = false;
        }
        
        if($ok === true && !in_array($thesisTopic->review->review_status, [2, 3])){ //Ha nem véglegesített a bírálat
            $error_msg = __('A bírálati lap nem tölthető fel.') . ' ' . __('A bírálat állapota alapján nem tölthető fel a bírálati lap.');
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
            $file = $this->getRequest()->getData('review_doc');
            
            if(empty($file['name'])){
                $thesisTopic->review->setError('review_doc', __('Fájl feltöltése kötelező.'));
            }else{
                $file['name'] = $this->addFileName($file['name'], ROOT . DS . 'files' . DS . 'review_docs');
                $thesisTopic->review = $this->Reviews->patchEntity($thesisTopic->review, ['review_doc' => $file]);
                $thesisTopic->review->review_status = 3;
                //$thesisTopic->review->cause_of_rejecting_confidentiality_contract = null;
            }
            
            if($this->Reviews->save($thesisTopic->review)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
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
                //Így a fájl a template-be ki lesz írva, mert ha nem kérem le, akkor a régi marad benne, amit feltöltött, vagy üres ha nem töltött fel
                $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                          'contain' => ['Reviews']])->first();
            }
        }
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Feltöltött bírálati lap véglegesítése
     * 
     * @param type $thesis_topic_id
     * @return type
     */
    public function finalizeUploadedReviewDoc($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A feltölött bírálati lap nem véglegesíthető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A feltölött bírálati lap nem véglegesíthető.') . ' ' . __('A téma nincs abban az állapotban, hogy a titoktartási szerződés letölthető lehetne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A feltölött bírálati lap nem véglegesíthető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha titkos és nincs elfogava a titoktartási szerződés
            $this->Flash->error(__('A feltölött bírálati lap nem véglegesíthető.') . ' ' . __('A titoktartási szerződés még nincs elfogadva.'));
            $ok = false;
        }elseif($thesisTopic->review->review_status != 3){ //Ha nincs a bírálati lap feltöltve
            $this->Flash->error(__('A feltölött bírálati lap nem véglegesíthető.') . ' ' . __('Először töltse fel a bírálati lapot.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'review', $thesisTopic->id]);
        
        $thesisTopic->review->review_status = 4; //Bírálati lap feltöltése véglegesítve
        if($this->Reviews->save($thesisTopic->review)) $this->Flash->success(__('Mentés sikeres.'));
        else $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        
        return $this->redirect(['action' => 'review', $thesisTopic->id]);
    }
    
    /**
     * Feltöltött bírálati lap letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getReviewDoc($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ //Nem "Bírálat alatt", "Dolgozat bírálva", "Dolgozat elfogadva" státuszban van
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A téma nincs abban az állapotban, hogy a feltöltött bírálati lap letölthető lehetne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha titkos és nincs elfogava a titoktartási szerződés
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A titoktartási szerződés még nincs elfogadva.'));
            $ok = false;
        }elseif(empty($thesisTopic->review->review_doc)){ //Ha nincs bírálati lap feltöltve
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Nincs feltöltve bírálati lap.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'review', $thesisTopic->id]);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'review_docs' . DS . $thesisTopic->review->review_doc,
                                                   ['download' => true, 'name' => $thesisTopic->review->review_doc]);

        return $response;
    }
    
    /**
     * Titoktartási szerződés feltöltése
     * 
     * @param type $thesis_topic_id Téma aonzosítója
     */
    public function uploadConfidentialityContract($thesis_topic_id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');       
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A titoktartási nyilatkozat nem tölthető fel.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálatt alatt" állapotban van
            $error_msg = __('A titoktartási nyilatkozat nem tölthető fel.') . ' ' . ' ' . __('A dolgozat nem bírálható állapotban van.');
            $ok = false;
        }elseif($thesisTopic->confidential !== true){ //Nem titkos a dolgozat
            $error_msg = __('A titoktartási nyilatkozat nem tölthető fel.') . ' ' . ' ' . __('A dolgozat nem titkos.');
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozatnak nem Ön a bírálója.');
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->review->confidentiality_contract_status == 4){ //Ha már el van fogadva a titoktartási szerződés
            $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A titoktartási szerződés már el van fogadva.');
            $ok = false;
        }elseif($thesisTopic->review->review_status != null){ //Ha bírálat már folyamatban van
            $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A bírálat már folyamatban van.');
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
            $file = $this->getRequest()->getData('confidentiality_contract');
            
            if(empty($file['name'])) $thesisTopic->review->setError('confidentiality_contract', __('Fájl feltöltése kötelező.'));
            else{
                $file['name'] = $this->addFileName($file['name'], ROOT . DS . 'files' . DS . 'confidentiality_contracts');
                $thesisTopic->review = $this->Reviews->patchEntity($thesisTopic->review, ['confidentiality_contract' => $file]);
                $thesisTopic->review->confidentiality_contract_status = 1;
            }
            
            
            if($this->Reviews->save($thesisTopic->review)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
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
                //Így a fájl a template-be ki lesz írva, mert ha nem kérem le, akkor a régi marad benne, amit feltöltött, vagy üres ha nem töltött fel
                $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                          'contain' => ['Reviews']])->first();
            }
        }
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Feltöltött titoktartási szerződés letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function finalizeConfidentialityContractUpload($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A dolgozat nem bírálatra vár státuszban van.'));
            $ok = false;
        }elseif($thesisTopic->confidential !== true){ //Nem titkos a dolgozat
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . ' ' . __('A dolgozat nem titkos.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if(empty($thesisTopic->review->confidentiality_contract)){ //Ha nincs titoktartási szerződés feltöltve
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('Nincs feltöltve titoktartási szerződés.'));
            $ok = false;
        }elseif($thesisTopic->review->confidentiality_contract_status == 4){ //Ha a titoktartási szerződés már el van fogadva
            $this->Flash->error(__('A titoktartási szerződés nem véglegesíthető.') . ' ' . __('A titoktartási szerződés már el van fogadva.'));
            $ok = false;
        }elseif($thesisTopic->review->review_status != null){ //Ha bírálat már folyamatban van
            $error_msg = __('A dolgozat részletei nem elérhetők.') . ' ' . __('A bírálat már folyamatban van.');
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]);
        
        $thesisTopic->review->confidentiality_contract_status = 2; //Véglegesítve, tanszékvezető ellenőrzésére vár
        
        if($this->Reviews->save($thesisTopic->review)){
            $this->Flash->success(__('Véglegesítés sikeres.'));
        }else{
            $this->Flash->error(__('Véglegesítés sikertelen. Próbálja újra!'));
        }
        
        return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]);
    }
    
    /**
     * Feltöltött titoktartási szerződés letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getUploadedConfidentialityContract($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('A téma nincs abban az állapotban, hogy a titoktartási szerződés letölthető lehetne.'));
            $ok = false;
        }elseif($thesisTopic->confidential !== true){ //Nem titkos a dolgozat
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . ' ' . __('A dolgozat nem titkos.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if(empty($thesisTopic->review->confidentiality_contract)){ //Ha nincs titoktartási szerződés feltöltve
            $this->Flash->error(__('A titoktartási szerződés nem elérhető.') . ' ' . __('Nincs feltöltve titoktartási szerződés.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id]);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'confidentiality_contracts' . DS . $thesisTopic->review->confidentiality_contract,
                                                   ['download' => true, 'name' => $thesisTopic->review->confidentiality_contract]);

        return $response;
    }
    
    /**
     * Bírálati lap letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function reviewDoc($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A bírálati lap nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A bírálati lap nem elérhető.') . ' ' . __('A dolgozat nincs abban az állapotban, hogy a bírálat véglegesíthető lenne.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews' => ['Reviewers', 'Questions'], 'Students'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A bírálati lap nem elérhető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){ //Ha a titoktartási szerződés még nincs elfogadva
            $this->Flash->error(__('A bírálati lap nem elérhető.') . ' ' . __('Először a feltöltött titoktartási szerződés el kell fogadnia a tanszékvezetőnek.'));
            $ok = false;
        }elseif($thesisTopic->review->review_status != 2){ //Ha a bírálat nem véglegesített
            $this->Flash->error(__('A bírálati lap nem elérhető.') . ' ' . __('Csak a véglegesített bírálati lapot töltheti le.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'review', $thesisTopic->id]);
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Alapbeállítások
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);
        $phpWord->setDefaultParagraphStyle(['spacing' => 1, 'spaceBefore' => 0, 'spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START]);
        
        //Szöveg stílusok
        $redTextFont = 'RedText';
        $phpWord->addFontStyle($redTextFont, ['color' => '800000']);
        $titleFont = 'TitleFont';
        $phpWord->addFontStyle($titleFont, ['size' => 16, 'bold' => true]);
        $boldTimesNewRoman = 'BoldTimesNewRoman';
        $phpWord->addFontStyle($boldTimesNewRoman, ['name' => 'Times New Roman', 'bold' => true]);
        
        //Bekezdés stílusok                                  'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12)]);
        $titlePara = 'TitleParagraph';
        $phpWord->addParagraphStyle($titlePara, ['spacing' => 1, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                                                 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        
        
        //Dokumentum készítése
        
        //Szekció
        $section = $phpWord->addSection(['marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.7),
                                         'orientation' => 'portrait', 'footerHeight' => 1.25, 'headerHeight' => 1.25]);
        
        //Első oldal
        
        //Cím
        $section->addText(($thesisTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')) . ' bírálati lap', $titleFont, $titlePara);
        $section->addTextBreak(1, ['size' => 10]);
        
        //Hallgató adatai
        $section->addText('Hallgató adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]); //Tab-nál kettős idézőjel!!!!!
        $section->addText('Név: ' . ($thesisTopic->has('student') ? $thesisTopic->student->name : '') .  "\tNeptun-kód: " . ($thesisTopic->has('student') ? $thesisTopic->student->neptun : ''), ['italic' => true], ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(10.25))]]);
        $section->addText($thesisTopic->title, ['size' => 14, 'bold' => true],
                          ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                           'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                           'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(18)]);
        //Bíráló adatai
        $section->addText('A bíráló adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('Név: ' . ($thesisTopic->has('review') ? ($thesisTopic->review->has('reviewer') ? $thesisTopic->review->reviewer->name : '') : ''), ['italic' => true]);
        $section->addText('Munkahely: ' . ($thesisTopic->has('review') ? ($thesisTopic->review->has('reviewer') ? $thesisTopic->review->reviewer->workplace : '') : ''), ['italic' => true]);
        $section->addText('Pozíció: ' . ($thesisTopic->has('review') ? ($thesisTopic->review->has('reviewer') ? $thesisTopic->review->reviewer->position : '') : ''), ['italic' => true]);
        $section->addTextBreak(1);
        
        //Pontszámok
        $section->addText('A szempontokra adott pontszámok és indoklásuk:');
        
        //Pontok és jegyek kiszámítása
        $total_points = 0;
        $grade = 1;
        if($thesisTopic->has('review')){
            $total_points = (empty($thesisTopic->review->structure_and_style_point) ? 0 : $thesisTopic->review->structure_and_style_point) +
                            (empty($thesisTopic->review->processing_literature_point) ? 0 : $thesisTopic->review->processing_literature_point) +
                            (empty($thesisTopic->review->writing_up_the_topic_point) ? 0 : $thesisTopic->review->writing_up_the_topic_point) +
                            (empty($thesisTopic->review->practical_applicability_point) ? 0 : $thesisTopic->review->practical_applicability_point);
        
            if(!empty($thesisTopic->review->structure_and_style_point) && !empty($thesisTopic->review->processing_literature_point) &&
               !empty($thesisTopic->review->writing_up_the_topic_point) && !empty($thesisTopic->review->practical_applicability_point)){
                
                if($total_points >= 45) $grade = 5;
                else if($total_points < 45 && $total_points >= 38) $grade = 4;
                else if($total_points < 38 && $total_points >= 31) $grade = 3;
                else if($total_points < 31 && $total_points >= 26) $grade = 2;
            }
        }
        
        //Táblázat (pontszámokhoz)
        $table1 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $textRun1 = $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.02), ['valign' => 'top', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])->addTextRun();
        $textRun1->addText('1. A dolgozat szerkezete, stílusa (max. 10 pont)', $boldTimesNewRoman);
        $textRun1->addTextBreak(1);
        $textRun1->addText($thesisTopic->has('review') ? $thesisTopic->review->cause_of_structure_and_style_point : '');
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.62), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'valign' => 'center', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])
                    ->addText($thesisTopic->has('review') ? (empty($thesisTopic->review->structure_and_style_point) ? 0 : $thesisTopic->review->structure_and_style_point) : 0, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $textRun2 = $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.02), ['valign' => 'top', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])->addTextRun();
        $textRun2->addText('2. Szakirodalom feldolgozása (max. 10 pont)', $boldTimesNewRoman);
        $textRun2->addTextBreak(1);
        $textRun2->addText($thesisTopic->has('review') ? $thesisTopic->review->cause_of_processing_literature_point : '');
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.62), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'valign' => 'center', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])
                    ->addText($thesisTopic->has('review') ? (empty($thesisTopic->review->processing_literature_point) ? 0 : $thesisTopic->review->processing_literature_point) : 0, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $textRun3 = $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.02), ['valign' => 'top', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])->addTextRun();
        $textRun3->addText('3. A téma kidolgozásának színvonala (max. 20 pont)', $boldTimesNewRoman);
        $textRun3->addTextBreak(1);
        $textRun3->addText($thesisTopic->has('review') ? $thesisTopic->review->cause_of_writing_up_the_topic_point : '');
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.62), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'valign' => 'center', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])
                    ->addText($thesisTopic->has('review') ? (empty($thesisTopic->review->writing_up_the_topic_point) ? 0 : $thesisTopic->review->writing_up_the_topic_point) : 0, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $textRun4 = $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.02), ['valign' => 'top', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])->addTextRun();
        $textRun4->addText('4. A dolgozat gyakorlati alkalmazhatósága (max. 10 pont)', $boldTimesNewRoman);
        $textRun4->addTextBreak(1);
        $textRun4->addText($thesisTopic->has('review') ? $thesisTopic->review->cause_of_practical_applicability_point : '');
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.62), ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'valign' => 'center', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])
                    ->addText($thesisTopic->has('review') ? (empty($thesisTopic->review->practical_applicability_point) ? 0 : $thesisTopic->review->practical_applicability_point) : 0, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.02), ['valign' => 'top', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])->addText('ÖSSZPONTSZÁM:', $boldTimesNewRoman);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.62), ['valign' => 'center', 'borderColor' => '000000', 'borderSize' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0.25)])
                    ->addText($total_points, null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $section->addPageBreak();
        
        //Második oldal
        
        $section->addText('Általános megjegyzések:', ['bold' => true]);
        $section->addText($thesisTopic->has('review') ? $thesisTopic->review->general_comments : '', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        $section->addTextBreak(1);
        $section->addText('Kérdések:', ['bold' => true]);
        $i = 0;
        if($thesisTopic->has('review') && $thesisTopic->review->has('questions')){
            foreach($thesisTopic->review->questions as $question){
               $section->addText(++$i . '.');
               $section->addText($question->question, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
               $section->addTextBreak(1);
            }
        }
        
        if($i < 3){
            for($j = $i; $j <= 3; $j++){
                $section->addText(++$i . '.');
                $section->addTextBreak(3);
            }
        }
        
        if($grade == 5) $grade_text = 'jeles';
        elseif($grade == 4) $grade_text = 'jó';
        elseif($grade == 3) $grade_text = 'közepes';
        elseif($grade == 2) $grade_text = 'elégséges';
        else $grade_text = 'elégtelen';
        
        $section->addTextBreak(1);
        $section->addText('Javasolt érdemjegy: ' . $grade_text, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        
        $section->addText('[hely][dátum]', $redTextFont);
        
        //Táblázat (aláíráshoz)
        $table2 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table2->addRow(null, ['cantSplit' => false]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.56), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.08), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3.39), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.69), ['valign' => 'top'])->addText('______________________', ['name' => 'Arial'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.3), ['valign' => 'top']);
        
        $table2->addRow(null, ['cantSplit' => false]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.56), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.08), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3.39), ['valign' => 'top']);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.69), ['valign' => 'top'])->addText('bíráló aláírás', ['size' => 10, 'name' => 'Arial'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table2->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.3), ['valign' => 'top']);
        
        //Fájl "letöltése"
        $filename =  'biralati_lap.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
    
    /**
     * Titoktartási nyilatkozat letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     * @return type
     */
    public function confidentialityContractDoc($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bírálat alatt" státuszban van
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A téma nem bírálható állapotban van.'));
            $ok = false;
        }else{
             $query = $this->Reviews->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); })
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews', 'Students' => ['Courses', 'CourseLevels', 'CourseTypes']])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozatnak nem Ön a bírálója'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Alapbeállítások
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);
        $phpWord->setDefaultParagraphStyle(['spacing' => 1, 'spaceBefore' => 0, 'spaceAfter' => 0, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        
        //Szöveg stílusok
        $redTextFont = 'RedText';
        $phpWord->addFontStyle($redTextFont, ['color' => '800000']);
        $subTitleFont = 'SubTitleFont';
        $phpWord->addFontStyle($subTitleFont, ['size' => 14, 'bold' => true]);
        
        //Bekezdés stílusok                                  'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12)]);
        $subTitlePara = 'SubTitleParagraph';
        $phpWord->addParagraphStyle($subTitlePara, ['spacing' => 1, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                                                    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        
        //Címsorok
        $headingOne = 1;
        $phpWord->addTitleStyle($headingOne, ['bold' => true, 'size' => 16],
                                             ['spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                                              'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
                                              'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        
        //Dokumentum készítése
        
        //Szekció
        $section = $phpWord->addSection(['marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.27),
                                         'orientation' => 'portrait', 'footerHeight' => 1.25, 'headerHeight' => 1.25]);
        
        //Első oldal
        
        //Cím
        $section->addTitle('Titoktartási nyilatkozat', $headingOne);
        //Adatok
        $section->addText('Adatok', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1);
        //Hallgató adatai
        $section->addText('Hallgató adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]); //Tab-nál kettős idézőjel!!!!!
        $section->addText('   Név: ' . ($thesisTopic->has('student') ? $thesisTopic->student->name : '') .  "\tNeptun-kód: " . ($thesisTopic->has('student') ? $thesisTopic->student->neptun : ''), null, ['tabs' => [new \PhpOffice\PhpWord\Style\Tab('left', \PhpOffice\PhpWord\Shared\Converter::cmToTwip(11.2))]]);
        $section->addText('   Szak: ' . ($thesisTopic->has('student') ? (($thesisTopic->student->has('course') ? $thesisTopic->student->course->name : '') . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? (' ' . $thesisTopic->student->course_level->name) : '') : '' )) : '' ));
        $section->addText('   Specializáció: ' . ($thesisTopic->has('student') ? $thesisTopic->student->specialisation : ''));
        $section->addText('   Tagozat: ' . ($thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? $thesisTopic->student->course_type->name : '') : '' ));
        $section->addTextBreak(2);
        //Szakdolgozat adatai
        $section->addText('A ' . ($thesisTopic->is_thesis == 0 ? 'diplomamunka' : 'szakdolgozat') . ' adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Cím: ' . $thesisTopic->title);
        $section->addText('   Nyelv: ' . ($thesisTopic->has('language') ? $thesisTopic->language->name : ''));
        $section->addTextBreak(1);
        //Cég adatai
        $section->addText('Partner-intézmény (cég, gazdasági társaság, intézmény) adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Név:');
        $section->addText('   Cím:');
        $section->addTextBreak(1);
        //A titoktartási nyilatkozatot adó személy adatai
        $section->addText('A titoktartási nyilatkozatot adó személy adatai', ['underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE]);
        $section->addText('   Név:');
        $section->addText('   Intézmény:');
        $section->addText('   A megbízás jellege: bíráló');
        $section->addText('   A titoktartási időszak vége:');
        $section->addTextBreak(2);
        
        //Nyilatkozat
        
        //Adatok
        $section->addText('Nyilatkozat', $subTitleFont, $subTitlePara);
        $section->addTextBreak(1, $subTitleFont, $subTitlePara);
        $section->addText('Alulírott tudomásul veszem, hogy a fent említett Hallgató ' . 
                           ($thesisTopic->is_thesis == 0 ? 'diplomamunkájának' : 'szakdolgozatának') . 
                            ' bírálata során olyan információk birtokába jutok, melyek a fenti Partner-intézmény szellemi tulajdonát képezik, így bizalmasan kezelendők.',
                            ['size' => 11], ['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        $section->addText('A dolgozatról illetve annak részeiről másolatot nem készítek, annak példányát munkám végeztével visszaadom vagy visszaküldöm annak (Partner-intézmény, bírálatot kérő tanszék, záróvizsga-bizottság),' .
                          ' akitől kaptam. A dolgozattal kapcsolatos megbízásom körén kívül szóban és írásban sem adok információt át más személyeknek, intézménynek a titoktartási időszak végéig.',
                          ['size' => 11], ['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)]);
        $section->addTextBreak(1, $subTitleFont, $subTitlePara);
        $section->addText('[hely][dátum]', $redTextFont);
        $section->addTextBreak(1);
        
        //Táblázat (aláíráshoz)
        $table1 = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
                                     'cellMarginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'cellMarginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1),
                                     'indent' => new \PhpOffice\PhpWord\ComplexType\TblWidth(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.1))]);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top']);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('____________________________', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        $table1->addRow(null, ['cantSplit' => false]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.97), ['valign' => 'top']);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(7.75), ['valign' => 'top'])->addText('aláírás', null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table1->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.29), ['valign' => 'top']);
        
        //Fájl "letöltése"
        $filename =  'titkositasi_nyilatkozat.docx';
        
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document");
        header('Content-Disposition: attachment; filename='.$filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        $objWriter->save("php://output");

        //Kilépés, nehogy a cakephp további dolgokat végezzen, mert akkor a fájl nem menne ki
        exit();
    }
}
