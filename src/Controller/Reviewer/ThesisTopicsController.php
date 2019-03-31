<?php
namespace App\Controller\Reviewer;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{   
    
    /**
     * Belső konzulenshez tartozó témák listája
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $query = $this->ThesisTopics->find();
        //Bírálat alatt levő és az adott bírálóhoz tartozó témák
        $thesisTopics = $query->where(['ThesisTopics.thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') /* Bíálat alatt*/, 'deleted !=' => true])
                              ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); })
                              ->contain(['Reviews']);
                                        

        $this->set(compact('thesisTopics'));
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id Téma azonosítója
     */
    public function details($id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id, 'deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bíálat alatt" státuszban van
            $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozat nem bírálható állapotban van.'));
            $ok = false;
        }else{
             $query = $this->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Languages', 'ThesisSupplements', 'Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A dolgozat részletei nem elérhetők.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }

}
