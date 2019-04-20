<?php
namespace App\Controller\Reviewer;

use App\Controller\AppController;

/**
 * ThesisSupplements Controller
 *
 * @property \App\Model\Table\ThesisSupplementsTable $ThesisSupplements
 *
 * @method \App\Model\Entity\ThesisSupplement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisSupplementsController extends AppController
{
    /**
     * Szakdolgozat/Diplomamunka melléklet letöltése
     * 
     * @param type $thesis_supplement_id
     */
    public function downloadFile($thesis_supplement_id = null){
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        if(empty($thesisSupplement) || empty($thesisSupplement->file)){
            $this->Flash->error(__('Melléklet nem létezik.'));
            return;
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesisSupplement->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bíálat alatt" státuszban van
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem bírálható állapotban van.'));
            $ok = false;
        }else{
             $query = $this->ThesisSupplements->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesisSupplement->thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('Először a titoktartási szerződés el kell fogadnia a tanszékvezetőnek.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $thesisSupplement->file,
                                                   ['download' => true, 'name' => $thesisSupplement->file]);

        return $response;
    }
    
        /**
     * Szakdolgozat/Diplomamunka Mellékletek letöltése egy ZIP-bem
     * 
     * @param type $thesis_topic_id
     * @return type
     */
    public function downloadSupplementsInZip($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Reviewers']]);
        $reviewer_id = $user->has('reviewer') ? $user->reviewer->id : '';
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
    
        $ok = true;
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A mellékletek nem elérhetőek.') . ' ' . __('Nem létező dolgozat.'));
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){ //Nem "Bíálat alatt" státuszban van
            $this->Flash->error(__('A mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem bírálható állapotban van.'));
            $ok = false;
        }else{
             $query = $this->ThesisSupplements->ThesisTopics->find();
             $thesisTopic = $query->where(['ThesisTopics.id' => $thesis_topic_id])
                                  ->matching('Reviews', function ($q) use($reviewer_id) { return $q->where(['Reviews.reviewer_id' => $reviewer_id]); }) //Az adott bírálóhoz tartozoik-e
                                  ->contain(['Reviews', 'ThesisSupplements'])
                                  ->first();
        
            if(empty($thesisTopic)){
                $this->Flash->error(__('A mellékletek nem elérhetőek.') . ' ' . __('A dolgozatnak nem Ön a bírálója.'));
                $ok = false;
            }
        }
        
        if($ok === true && $thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){
            $this->Flash->error(__('A mellékletek nem elérhetőek.') . ' ' . __('Először a titoktartási szerződés el kell fogadnia a tanszékvezetőnek.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        # create a new zipstream object
        $zip = new \ZipStream\ZipStream('mellekletek.zip');

        $i = 0;
        foreach($thesisTopic->thesis_supplements as $supplement){
            if(!empty($supplement->file)){
                $i++;
                $zip->addFileFromPath($supplement->file, ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $supplement->file);
            }
        }
        
        if($i < 1){//Ha nem volt melléklet
            $this->Flash->error(__('A mellékletek nem elérhetőek.') . ' ' . __('A dolgozathoz nem tartoznak mellékletek.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);            
        }

        # finish the zip stream
        $zip->finish();
    }
}
