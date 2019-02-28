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
    
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') == 'decideToContinueAfterFailedFirstThesisSubject') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Témalista
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak azokat a témákat látja, amelyet a belső konzulens már elfogadott
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id NOT IN' => [1, 2, 3, 4, 5, 6, 7] /* Már eljutott a tanszékvezetőig */],
                                                          'contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

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

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $thesisTopic_id]])->first();
            
            $ok = true;
            if(empty($thesisTopic)){
                $this->Flash->error(__('Erről a témáról nem dönthet.') . ' ' . __('A téma nem létezik.'));
                $ok = false;
            }elseif($thesisTopic->thesis_topic_status_id != 8){ //Nem "A téma tanszékvezetői döntésre vár" státuszban van
                $this->Flash->error(__('Erről a témáról nem dönthet.') . ' ' . __('Nem tanszékvezetői döntésre vár.'));
                $ok = false;
            }
            
            if(!$ok) return $this->redirect(['action' => 'index']);
            
            //Elutasítás vagy elfogadás esetén, ha van külső konzulens, akkor külső konzulensi ellenőrzésre vár státuszú lesz, ha nincs, akkor pedig elfogadva
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? 9 : ($thesisTopic->cause_of_no_external_consultant === null ? 10 : 12);

            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!!'));
            }else{
                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id Téma azonosítója
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'], 'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages']])->first();
    
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [1, 2, 3, 4, 5, 6, 7])){ //Tanszékvezető döntése alatti státuszokban van
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.' );
            $ok = false;
        }
        
        if(!$ok) return $this->redirect (['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * 
     * @param type $id Téma azonosítója
     */
    public function decideToContinueAfterFailedFirstThesisSubject($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $this->loadModel('Users');
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['ThesisTopicStatuses']])->first();
        
        $error_msg = '';
        $no_thesis_topic = false;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('Nem dönthet.') . ' ' . __('Nem létező téma.');
            $no_thesis_topic = true;
        }elseif($thesisTopic->thesis_topic_status_id != 13){ //Nem "Első diplomakurzus sikertelen, tanszékvezető döntésére vár" státuszban van
            $error_msg = __('Nem dönthet.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.';
            $no_thesis_topic = true;
        }
        
        //Ha a feltételeknek nem megfelelő téma
        if($no_thesis_topic){
            $this->set(compact('no_thesis_topic', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $decide_to_continue = $this->getRequest()->getData('decide_to_continue');
            
            if($decide_to_continue === null || !in_array($decide_to_continue, [0, 1])){
                $thesisTopic->setError('custom', __('A döntésnek "0"(nem) vagy "1"(igen) értéket kell felvennie!'));
            }else{
                if($decide_to_continue == 0){ //Új témát kell választania
                    $thesisTopic->thesis_topic_status_id = 14; //Téma elutasítva (első diplomakurzus sikertelen)
                }else{ //Javíthatja a diplomakurzust a jelenlegi témával
                    $thesisTopic->thesis_topic_status_id = 12; //Elfogadva
                    $thesisTopic->first_thesis_subject_failed_suggestion = null; // Első diplomakurzus még nem dőlt el, hogy teljesítette-e
                }
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $thesisTopic->getErrors();
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
        
        $this->set(compact('thesisTopic' ,'no_thesis_topic', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
