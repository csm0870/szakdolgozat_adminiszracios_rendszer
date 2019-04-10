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
     * Szakdolgozat/Diplomamunka melléklet letöltése
     * 
     * @param type $thesis_supplement_id
     */
    public function downloadFile($thesis_supplement_id = null){
        $thesisSupplement = $this->ThesisSupplements->find('all', ['conditions' => ['id' => $thesis_supplement_id]])->first();
        if(empty($thesisSupplement) || empty($thesisSupplement->file)){
            $this->Flash->error(__('Melléklet nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['id' => $thesisSupplement->thesis_topic_id]])->first();

        if(empty($thesisTopic)){
            $this->Flash->error(__('A melléklet nem elérhető.') . ' ' . __('A dolgozat nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
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
        $thesisTopic = $this->ThesisSupplements->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesis_topic_id],
                                                                            'contain' => ['ThesisSupplements']])->first();

        if(empty($thesisTopic)){
            $this->Flash->error(__('Mellékletek nem elérhetőek.') . ' ' . __('A téma nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        $student = $this->ThesisSupplements->ThesisTopics->Students->find('all', ['conditions' => ['id' => $thesisTopic->student_id]])->first();
        
        # create a new zipstream object
        $zip = new \ZipStream\ZipStream((empty($student) ? '' : ($student->neptun == '' ? '' : $student->neptun . '_')) . 'mellekletek.zip');

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
        
        if(empty($thesisTopic)){
            $this->Flash->error(__('Mellékletek nem elérhetőek.') . ' ' . __('A téma nem létezik.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index']);
        }
        
        if($this->ThesisSupplements->delete($thesisSupplement)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect($this->referer(null, true));
    }
}
