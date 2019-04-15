<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Information Controller
 *
 * @property \App\Model\Table\InformationTable $Information
 *
 * @method \App\Model\Entity\Information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InformationController extends AppController
{
    /**
     * Témakitöltési időszak beállítása
     */
    public function setFillingInPeriod(){
        $info = $this->Information->find('all')->first();
        
        if(empty($info)){
            $info = $this->Information->newEntity();
        }
        
        if($this->getRequest()->is(['post', 'put', 'patch'])){
            $info = $this->Information->patchEntity($info, $this->getRequest()->getData());
            if($this->Information->save($info)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $this->Flash->success(__('Mentés sikertelen.'));
            }
        }
        
        $this->set(compact('info'));
    }
    
    /**
     * Titoktartási kérelmi szabályzat szövegének beállítása
     */
    public function setEncryptionRequlation(){
        $info = $this->Information->find('all')->first();
        
        if(empty($info)){
            $info = $this->Information->newEntity();
        }
        
        if($this->getRequest()->is(['post', 'put', 'patch'])){
            $info = $this->Information->patchEntity($info, $this->getRequest()->getData());
            if($this->Information->save($info)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $this->Flash->success(__('Mentés sikertelen.'));
            }
        }
        
        $this->set(compact('info'));
    }
}
