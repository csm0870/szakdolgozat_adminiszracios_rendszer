<?php
namespace App\Controller\Student;

use App\Controller\AppController;

/**
 * OfferedTopics Controller
 *
 * @property \App\Model\Table\OfferedTopicsTable $OfferedTopics
 *
 * @method \App\Model\Entity\OfferedTopic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OfferedTopicsController extends AppController
{
    /**
     * Belső konzulens kiírt témáinak listája
     *
     * @return \Cake\Http\Response|void
     */
    public function index(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $offeredTopics = $this->OfferedTopics->find('all',['contain' => ['InternalConsultants']])
                                             ->notMatching('ThesisTopics', function($q){ //Azon témák, amelyekhez nem tartozik foglalás
                                                 return $q->where(['ThesisTopics.id IS NOT' => null]);
                                             });
        
        $this->set(compact('offeredTopics'));
    }
    
    /**
     * Témaajánlat részletei
     * 
     * @param type $id OfferedTopic azonosító
     * @return type
     */
    public function details($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }
        
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id], 'contain' => ['InternalConsultants']])->first();
        
        $ok = true; 
        if(empty($offeredTopic)){
            $ok = false;
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma nem létezik.'));
        }else{
            //Megnézzük, hogy van-e hozzá témafoglalás
            $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id]])->notMatching('ThesisTopics', function($q){ //Azon témák, amelyekhez nem tartozik foglalás
                                                 return $q->where(['ThesisTopics.id IS NOT' => null]);
                                             })->first();
            
            if(empty($offeredTopic)){ //Vagyis nincs hozzá foglalás
                $ok = false;
                $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma már le van foglalva.'));
            }
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        $can_add_topic = $this->Students->canAddTopic($data['student_id']);
        $this->set(compact('offeredTopic', 'can_add_topic'));
    }
    
    /**
     * Téma lefoglalása
     * 
     * @param type $id OfferedTopic azonosító
     * @return type
     */
    public function book($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('A foglaláshoz meg kell adnia az adatait!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        if(!$this->Students->canAddTopic($data['student_id'])){
            $this->Flash->error(__('Nem foglalhat témát. Már rendelkezik folyamatban lévő témával.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id]])->first();
        
        $ok = true; 
        if(empty($offeredTopic)){
            $ok = false;
            $this->Flash->error(__('A témát nem foglalhatja le.') . ' ' . __('A téma nem létezik.'));
        }elseif($offeredTopic->internal_consultant_id === null){
            $ok = false;
            $this->Flash->error(__('A témát nem foglalhatja le.') . ' ' . __('A témához nincs belső konzulens.'));
        }else{
            //Megnézzük, hogy van-e hozzá témafoglalás
            $offeredTopic = $this->OfferedTopics->find('all', ['conditions' => ['OfferedTopics.id' => $id]])->notMatching('ThesisTopics', function($q){ //Azon témák, amelyekhez nem tartozik foglalás
                                                 return $q->where(['ThesisTopics.id IS NOT' => null]);
                                             })->first();
            
            if(empty($offeredTopic)){ //Vagyis nincs hozzá foglalás
                $ok = false;
                $this->Flash->error(__('A témát nem foglalhatja le.') . ' ' . __('A téma már le van foglalva.'));
            }
        }
        
        if($ok === false) return $this->redirect(['action' => 'index']);
        
        //ThesisTopic rekord létrehozása az adatok átmásolásával
        $this->loadModel('ThesisTopics');
        $thesisTopic = $this->ThesisTopics->newEntity();
        $thesisTopic->title = $offeredTopic->title;
        $thesisTopic->description = $offeredTopic->description;
        $thesisTopic->internal_consultant_id = $offeredTopic->internal_consultant_id;
        
        if($offeredTopic->has_external_consultant === true){ //Ha van külső konzulens
            $thesisTopic->cause_of_no_external_consultant = null;
            $thesisTopic->external_consultant_name =  $offeredTopic->external_consultant_name;
            $thesisTopic->external_consultant_address = $offeredTopic->external_consultant_address;
            $thesisTopic->external_consultant_workplace = $offeredTopic->external_consultant_workplace;
            $thesisTopic->external_consultant_position = $offeredTopic->external_consultant_position;
            $thesisTopic->external_consultant_email = $offeredTopic->external_consultant_email;
            $thesisTopic->external_consultant_phone_number = $offeredTopic->external_consultant_phone_number;
        }else{
            $thesisTopic->cause_of_no_external_consultant = __('A belső konzulens nem jelölt ki külső konzulenst, de adható hozzá.');
        }
        
        $thesisTopic->thesis_topic_status_id = 2;
        $thesisTopic->offered_topic_id = $offeredTopic->id;
        $thesisTopic->student_id = $data['student_id'];

        if($this->ThesisTopics->save($thesisTopic)){
            $this->Flash->success(__('Lefoglalás sikeres.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->Flash->error(__('Lefoglalás sikertelen. Próbálja újra!') . print_r($thesisTopic->getErrors(), true));
        return $this->redirect(['action' => 'details', $offeredTopic->id]);
    }
}
