<?php
namespace App\Controller\InternalConsultant;

use App\Controller\AppController;

/**
 * Reviewers Controller
 *
 * @property \App\Model\Table\ReviewersTable $Reviewers
 *
 * @method \App\Model\Entity\Reviewer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReviewersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') != 'index') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Bírálók listája
     */
    public function index()
    {
        $reviewers = $this->Reviewers->find('all');
        $this->set(compact('reviewers'));
    }

    /**
     * Bíráló hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $saved = true;
        $error_ajax = "";
        $reviewer = $this->Reviewers->newEntity();
        if ($this->request->is('post')) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $reviewer->getErrors();
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
        
        $this->set(compact('reviewer', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reviewer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $ok = true;
        $error_msg = '';
        
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($reviewer)){
            $ok = false;
            $error_msg = __('Helytelen aznosító.') . __('A bíráló nem létezik.');
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reviewer = $this->Reviewers->patchEntity($reviewer, $this->request->getData());
            if ($this->Reviewers->save($reviewer)){
                $this->Flash->success(__('Mentés sikeres.'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $reviewer->getErrors();
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
        
        $this->set(compact('reviewer', 'saved', 'error_ajax', 'ok', 'error_msg'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Bíráló törlése
     *
     * @param string|null $id Bíráló aznosítója
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reviewer = $this->Reviewers->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($reviewer)){
            $this->Flash->success(__('Helytelen aznosító.') . __('A bíráló nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->Reviewers->delete($reviewer)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
