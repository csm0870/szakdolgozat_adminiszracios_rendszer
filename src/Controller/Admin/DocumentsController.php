<?php
namespace App\Controller\Admin;

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
     * 
     * @param \Cake\Event\Event $event
     */
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Dokumentumok listája
     */
    public function index(){
        $documents = $this->Documents->find('all');
        $this->set(compact('documents'));
    }
    
    /**
     * Konzultációs alkalom szerkesztése
     *
     * @param string|null $id Konzultációs alkalom egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $document = $this->Documents->find('all', ['conditions' => ['id' => $id]])->first();
        
        $error_msg = '';
        $ok = true;
        if(empty($document)){ //Ha nem létezik a dokumentum
            $error_msg = __('A kért dokumentum nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->request->is(['patch', 'post', 'put'])){
            $file = $this->getRequest()->getData('file');
            
            if(empty($file['name'])){
                $document->setError('file', __('Fájl feltöltése kötelező.'));
            }else{
                $file['name'] = $this->addFileName($file['name'], ROOT . DS . 'files' . DS . 'documents');
                $document = $this->Documents->patchEntity($document, ['file' => $file]);
            }
            
            if($this->Documents->save($document)) {
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $document->getErrors();
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
        
        $this->set(compact('document', 'saved', 'error_ajax', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
