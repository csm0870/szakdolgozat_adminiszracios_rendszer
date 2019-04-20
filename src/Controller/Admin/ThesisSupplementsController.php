<?php
namespace App\Controller\Admin;

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
     * Melléklet törlése
     *
     * @param string|null $id Melléklet egyedi azonosítója
     */
    public function delete($thesis_supplement_id = null){
        $this->request->allowMethod(['post', 'delete']);
       
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        if(empty($thesisSupplement)){
            $this->Flash->error(__('Melléklet nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id]])->first();
        
        if(empty($thesisTopic)){
            $this->Flash->error(__('Mellékletek nem elérhetőek.') . ' ' . __('A téma nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        if($this->ThesisSupplements->delete($thesisSupplement)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect($this->referer(null, true));
    }
}
