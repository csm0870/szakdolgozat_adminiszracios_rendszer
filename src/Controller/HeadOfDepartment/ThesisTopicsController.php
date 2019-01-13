<?php
namespace App\Controller\HeadOfDepartment;

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
     * Témalista
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak azokat a témákat látja, amelyet a belső konzulens már elfogadott
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['accepted_by_internal_consultant IS NOT' => null, 'deleted !=' => true],
                                                          'contain' => ['Students', 'InternalConsultants'], 'order' => ['ThesisTopics.modified' => 'ASC']]);

        $this->set(compact('thesisTopics'));
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

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesisTopic_id, 'modifiable' => false,
                                                                              'accepted_by_internal_consultant' => true, //Ha a belső konzulens elfogadta
                                                                              'accepted_by_head_of_department IS' => null, //Tanszékvezető konzulens még nem döntött
                                                                              'accepted_by_external_consultant IS' => null //Külső konzulens még nem döntött
                                                                              ]])->first();

            if(empty($thesisTopic)){
                $this->Flash->error(__('Ezt a témát nem fogadhatja el. Már vagy döntést hozott, vagy nem Önhöz tartozik, vagy még nem véglegesített, vagy már el lett utasítva a téma!'));
                return $this->redirect(['action' => 'index']);
            }

            $thesisTopic->accepted_by_head_of_department = $accepted;
            //Többi resetelése
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
