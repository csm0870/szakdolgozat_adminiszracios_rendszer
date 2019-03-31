<?php
namespace App\Controller;

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
        $group_id = $this->Auth->user('group_id');
        $prefix = '';
        if($group_id == 1){
            $prefix = 'admin';
        }elseif($group_id == 2){
            $prefix = 'internal_consultant';
        }elseif($group_id == 3){
            $prefix = 'head_of_department';
        }elseif($group_id == 4){
            $prefix = 'topic_manager';
        }elseif($group_id == 5){
            $prefix = 'thesis_manager';
        }elseif($group_id == 6){
            $prefix = 'student';
        }elseif($group_id == 7){
            $prefix = 'reviewer';
        }elseif($group_id == 8){
            $prefix = 'final_exam_organizer';
        }
        
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => (empty($thesisSupplement) ? '-1' : $thesisSupplement->thesis_topic_id), 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem létezik.'));
            $ok = false;
        }elseif(empty($thesisSupplement) || empty($thesisSupplement->file)){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('Melléklet nem létezik.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
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
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true],
                                                                            'contain' => ['ThesisSupplements', 'Students']])->first();
        
        $group_id = $this->Auth->user('group_id');
        
        $prefix = '';
        if($group_id == 1){
            $prefix = 'admin';
        }elseif($group_id == 2){
            $prefix = 'internal_consultant';
        }elseif($group_id == 3){
            $prefix = 'head_of_department';
        }elseif($group_id == 4){
            $prefix = 'topic_manager';
        }elseif($group_id == 5){
            $prefix = 'thesis_manager';
        }elseif($group_id == 6){
            $prefix = 'student';
        }elseif($group_id == 7){
            $prefix = 'reviewer';
        }elseif($group_id == 8){
            $prefix = 'final_exam_organizer';
        }
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem létezik.'));
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        # create a new zipstream object
        $zip = new \ZipStream\ZipStream(($thesisTopic->has('student') ? ($thesisTopic->student->neptun == '' ? '' : $thesisTopic->student->neptun . '_') : '' ) . 'mellekletek.zip');

        $i = 0;
        foreach($thesisTopic->thesis_supplements as $supplement){
            if(!empty($supplement->file)){
                $i++;
                $zip->addFileFromPath($supplement->file, ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $supplement->file);
            }
        }
        
        if($i < 1){//Ha nem volt melléklet
            $this->Flash->error(__('A dolgozat mellékletek nem elérhezőek.') . ' ' . __('A dolgozathoz nem tartoznak mellékletek.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);            
        }

        # finish the zip stream
        $zip->finish();
    }
}
