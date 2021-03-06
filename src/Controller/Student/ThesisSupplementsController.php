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
     * Melléklet letöltése
     * 
     * @param type $thesis_supplement_id
     */
    public function downloadFile($thesis_supplement_id = null){
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        if(empty($thesisSupplement) || empty($thesisSupplement->file)){
            $this->Flash->error(__('Melléklet nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Students']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id, 'deleted !=' => true]])->first();
        $ok = true;
        if(empty($thesisTopic)){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem létezik.'));
            $ok = false;
        }elseif($thesisTopic->student_id != ($user->has('student') ? $user->student->id : '-1')){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
            $ok = false;
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        $response = $this->getResponse()->withFile(ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $thesisSupplement->file,
                                                   ['download' => true, 'name' => $thesisSupplement->file]);

        return $response;
    }
    
    /**
     * Mellékletek letöltése egy ZIP-bem
     * 
     * @param type $thesis_topic_id
     * @return type
     */
    public function downloadSupplementsInZip($thesis_topic_id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Students']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id, 'ThesisTopics.deleted !=' => true],
                                                                            'contain' => ['ThesisSupplements']])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem létezik.'));
        }elseif($thesisTopic->student_id != ($user->has('student') ? $user->student->id : '-1')){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        # create a new zipstream object
        $zip = new \ZipStream\ZipStream(($user->has('student') ? ($user->student->neptun == '' ? '' : $user->student->neptun . '_') : '' ) . 'mellekletek.zip');

        $i = 0;
        foreach($thesisTopic->thesis_supplements as $supplement){
            if(!empty($supplement->file)){
                $i++;
                $zip->addFileFromPath($supplement->file, ROOT . DS . 'files' . DS . 'thesis_supplements' . DS . $supplement->file);
            }
        }
        
        if($i < 1){//Ha nem volt melléklet
            $this->Flash->error(__('A dolgozat mellékletek nem elérhezőek.') . ' ' . __('A dolgozathoz nem tartoznak mellékletek.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }

        # finish the zip stream
        $zip->finish();
    }
    
    /**
     * Melléklet törlése
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
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Students']]);
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id, 'ThesisTopics.deleted !=' => true]])->first();
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $this->Flash->error(__('A dolgozat mellékletek nem elérhetőek.') . ' ' . __('A dolgozat nem létezik.'));
        }elseif($thesisTopic->student_id != ($user->has('student') ? $user->student->id : '-1')){
            $ok = false;
            $this->Flash->error(__('A melléklet nem törölhető.') . ' ' . __('A dolgozat nem Önhöz tartozik.'));
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement'),
                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected')])){
            $ok = false;
            $this->Flash->error(__('A melléklet nem törölhető.') . ' ' . __('A dolgozat állapota alapján már nem változtathatók a mellékletek.'));
        }
        
        if($ok === false) return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        
        if($this->ThesisSupplements->delete($thesisSupplement)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect($this->referer(null, true));
    }
}
