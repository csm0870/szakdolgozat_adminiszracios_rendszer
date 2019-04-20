<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Years Controller
 *
 * @property \App\Model\Table\YearsTable $Years
 *
 * @method \App\Model\Entity\Year[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class YearsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Tanévek listája
     */
    public function index(){
        $years = $this->Years->find('all');
        $this->set(compact('years'));
    }
    
    /**
     * Tanév hozzáadása
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
                
        $saved = true;
        $error_ajax = "";
        
        $year = $this->Years->newEntity();
        if($this->getRequest()->is('post')){
            $year = $this->Years->patchEntity($year, $this->getRequest()->getData());
            if($this->Years->save($year)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $year->getErrors();
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
        
        $this->set(compact('year', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Tanév szerkesztése
     *
     * @param string|null $id Tanév egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $year = $this->Years->find('all', ['conditions' => ['id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($year)){ //Ha nem létezik a év
            $error_msg = __('A kért tanév nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $year = $this->Years->patchEntity($year, $this->getRequest()->getData());
            if($this->Years->save($year)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $year->getErrors();
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
        
        $this->set(compact('year', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Tanév törlése
     *
     * @param string|null $id Tanév egyedi aznosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $year = $this->Years->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($year)){
            $this->Flash->error(__('Tanév nem törölhető.') . ' ' . __('A tanév nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->Years->delete($year)) $this->Flash->success(__('Törlés sikeres'));
        else $this->Flash->error(__('Törlés sikertelen. Kérjük próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
