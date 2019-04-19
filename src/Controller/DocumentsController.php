<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Documents Controller
 *
 * @property \App\Model\Table\DocumentsTable $Documents
 *
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DocumentsController extends AppController
{

    /**
     * Fájl letöltése
     * 
     * @param type $document_id Dokumentum azonosója
     */
    public function downloadFile($document_id = null){
        $document = $this->Documents->find('all', ['conditions' => ['id' => $document_id]])->first();
        
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
        
        if(empty($document) || empty($document->file)){
            $this->Flash->error(__('Fájl nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'documents' . DS . $document->file,
                                                   ['download' => true, 'name' => $document->file]);

        return $response;
    }
}
