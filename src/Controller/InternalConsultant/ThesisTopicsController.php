<?php
namespace App\Controller\InternalConsultant;

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
     * Belső konzulenshez tartozó témák listája
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak a véglegesített és a hozzá tartozó témákat látja
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['internal_consultant_id' => ($user->has('internal_consultant') ? $user->internal_consultant->id : null),
                                                                           'modifiable' => false, 'deleted !=' => true],
                                                          'contain' => ['Students'], 'order' => ['ThesisTopics.modified' => 'ASC']]);

        $this->set(compact('thesisTopics'));
    }

    /**
     * Téma törlése a belső konzulens által (nem tényleges fizikai törlés)
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->get($id);

        $can_be_deleted = false;

        //Akkor törölheti, ha már nincs bírálati folyamatban
        if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->accepted_by_external_consultant !== null){
            $can_be_deleted = true;
        }elseif($thesisTopic->accepted_by_head_of_department !== null){
            if($thesisTopic->accepted_by_head_of_department === false){
                $can_be_deleted = true;
            }elseif($thesisTopic->cause_of_no_external_consultant !== null){
                $can_be_deleted = true;
            }
        }elseif($thesisTopic->accepted_by_internal_consultant === false){
            $can_be_deleted = true;
        }

        if(!$can_be_deleted){
            $this->Flash->error(__('A téma nem törölhető. Az bírálata még folyamatban van.'));
            return $this->redirect(['action' => 'index']);
        }

        $thesisTopic->deleted = true;
        if ($this->ThesisTopics->save($thesisTopic)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen. Kérem próbálja újra!'));
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Táma elfogadása vagy elutasítása
     * @return type
     */
    public function accept(){
        if($this->getRequest()->is('post')){
            $thesisTopic_id = $this->getRequest()->getData('thesis_topic_id');
            $accepted = $this->getRequest()->getData('accepted');

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $this->Flash->error(__('Helytelen kérés. Próbálja újra!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->loadModel('Users');
            
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesisTopic_id, 'modifiable' => false,
                                                                              'internal_consultant_id' => $user->has('internal_consultant') ? $user->internal_consultant->id : null, //Belso konzulens a saját témája
                                                                              'accepted_by_internal_consultant IS' => null, //Belső konzulens még nem döntött
                                                                              'accepted_by_head_of_department IS' => null, //Tanszékvezető konzulens még nem döntött
                                                                              'accepted_by_external_consultant IS' => null //Külső konzulens még nem döntött
                                                                              ]])->first();

            if(empty($thesisTopic)){
                $this->Flash->error(__('Ezt a témát nem fogadhatja el. Már vagy döntést hozott, vagy nem Önhöz tartozik, vagy még nem véglegesített, vagy már el lett utasítva a téma!'));
                return $this->redirect(['action' => 'index']);
            }
            
            $thesisTopic->accepted_by_internal_consultant = $accepted;
            //Többi resetelése
            $thesisTopic->accepted_by_head_of_department = null;
            $thesisTopic->accepted_by_external_consultant = null;

            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!!'));
            }else{
                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }
}
