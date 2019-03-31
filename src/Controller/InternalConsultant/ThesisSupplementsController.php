<?php
namespace App\Controller\InternalConsultant;

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
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '-1')){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
            $ok = false;
        }
        
        if($ok === true) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
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
    public function downloadSupplementInZip($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true],
                                                                            'contain' => ['ThesisSupplements']])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem létezik.'));
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '-1')){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
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
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem tartoznak mellékletek.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);            
        }

        # finish the zip stream
        $zip->finish();
    }
}
