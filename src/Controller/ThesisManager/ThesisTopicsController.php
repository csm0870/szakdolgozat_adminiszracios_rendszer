<?php
namespace App\Controller\ThesisManager;

use App\Controller\AppController;

/**
 * ThesisTopics Controller
 *
 * @property \App\Model\Table\ThesisTopicsTable $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ThesisTopicsController extends AppController
{
    /**
     * Szakdolgozatkezelő témalista(szakdolgozatlista)
     */
    public function index(){
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'modifiable' => false, 'thesis_topic_status_id IN' => [14]],
                                                          'contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

        $this->set(compact('thesisTopics'));
    }
    
    
    /**
     * Témarészletek
     * 
     * @param type $id
     * @return type
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id], 'contain' => ['ThesisSupplements']])->first();
        $student = $this->ThesisTopics->Students->find('all', ['conditions' => ['Students.user_id' => $thesisTopic->student_id], 'contain' => ['FinalExamSubjects' => ['Years']]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [14])){ //A szakdolgozati feltöltés nincs véglegesítve
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A szakdolgozat felöltése még nincs véglegesítve.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('thesisTopic', 'student'));
    }
}
