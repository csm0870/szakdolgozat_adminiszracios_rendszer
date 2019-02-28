<?php
namespace App\Controller\Student;

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
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Students']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id]])->first();
        if($thesisTopic->student_id != ($user->has('student') ? $user->student->id : '-1')){
            $this->Flash->error(__('A szakdolgozat/diplomamunka nem Önhöz tartozik.'));
            return;
        }
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $thesisSupplement->file,
                                                   ['download' => true, 'name' => $thesisSupplement->file]);

        return $response;
    }
    
    /**
     * Delete method
     *
     * @param string|null $id Thesis Supplement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($thesis_supplement_id = null){
        $this->request->allowMethod(['post', 'delete']);
       
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        if(empty($thesisSupplement)){
            $this->Flash->error(__('Melléklet nem létezik.'));
            return $this->redirect($this->referer(null, true));
        }
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id]])->first();
        $ok = true;
        if($thesisTopic->student_id != $this->Auth->user('id')){
            $ok = false;
            $this->Flash->error(__('A melléklet nem törölhető.') . ' ' . __('A szakdolgozat/diplomamunka nem Önhöz tartozik.'));
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [16, 17, 19])){
            $ok = false;
            $this->Flash->error(__('A melléklet nem törölhető.') . ' ' . __('A szakdolgozat/diplomamunka állapota alapján már nem változtathatók a mellékletek.'));
        }
        
        if($ok === false) return $this->redirect($this->referer(null, true));
        
        if ($this->ThesisSupplements->delete($thesisSupplement)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        }

        return $this->redirect($this->referer(null, true));
    }
}
