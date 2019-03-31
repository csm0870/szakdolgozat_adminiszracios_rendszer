<?php
namespace App\Controller\ThesisManager;

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
    /**
     * Bírálat ellenőrzése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function checkReview($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true],
                                                                  'contain' => ['Reviews' => ['Reviewers', 'Questions']]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ //Nem "A dolgozat bírálva", "A dolgozat elfogadva" státuszban van
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozat még nem lett bírálva.'));
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozathoz nem tartozik bírálat.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Feltöltött bírálati lap letöltése
     * 
     * @param type $thesis_topic_id Téma azonosítója
     */
    public function getReviewDoc($thesis_topic_id = null){
        $thesisTopic = $this->Reviews->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true],
                                                                  'contain' => ['Reviews']])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccpeted')])){ //Nem "A dolgozat bírálva", "A dolgozat elfogadva" státuszban van
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A dolgozat még nem lett bírálva.'));
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $this->Flash->error(__('A feltölött bírálati lap nem elérhető.') . ' ' . __('A dolgozathoz nem tartozik bírálat.'));
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
}
