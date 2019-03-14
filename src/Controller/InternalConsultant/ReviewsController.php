<?php
namespace App\Controller\InternalConsultant;

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
     * Bírálat megtekintése
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
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [24, 25])){ //Nem "a dolgozat bírálva", vagy "A dolgozat elfogadva" státuszban van
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozat még nem lett bírálva, vagy bírálat alatt van.'));
            $ok = false;
        }elseif($thesisTopic->has('review') == false){ //Nincs bírálat a dolgozathoz
            $this->Flash->error(__('A bírálat nem elérhető.') . ' ' . __('A dolgozathoz nem tartozik bírálat.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
}
