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
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if($this->getRequest()->getParam('action') == 'acceptThesisSupplements') $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Szakdolgozatkezelő témalista(szakdolgozatlista)
     */
    public function index(){
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id IN' => [18, 19, 20, 21, 22, 23, 24, 25]],
                                                          'contain' => ['Students', 'InternalConsultants', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

        $this->set(compact('thesisTopics'));
    }
    
    
    /**
     * Téma részletek
     * 
     * @param type $id
     * @return type
     */
    public function details($id = null){
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                       'ThesisTopicStatuses', 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages', 'ThesisSupplements',
                                                                       'Reviews' => ['Reviewers']]])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [18, 19, 20, 21, 22, 23, 24, 25])){ //A szakdolgozati feltöltés nincs véglegesítve
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A dolgozat még nincs abban az állapotban, hogy elérheti.'));
            $ok = false;
        }
        
        if(!$ok) return $this->redirect(['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    /**
     * Mellékletek elfogadása/elutasítása
     * 
     * @return type
     */
    public function acceptThesisSupplements($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id]])->first();
        
        $error_msg = '';
        
        $ok = true;
        if(empty($thesisTopic)){
            $ok = false;
            $error_msg = __('A mellékletek nem bírálhatóak.') . ' ' . __('A téma nem létezik.');
        }elseif($thesisTopic->thesis_topic_status_id != 18){ //A szakdolgozati feltöltés nincs véglegesítve
            $ok = false;
            $error_msg = __('A mellékletek nem bírálhatóak.') . ' ' . __('A szakdolgozat felöltése még nincs véglegesítve.');
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $accepted = $this->getRequest()->getData('accepted');
            
            if($accepted === null || !in_array($accepted, [0, 1])){
                $thesisTopic->review->setError('custom', __('A döntésnek "0" (nem) vagy "1" (igen) értéket kell felvennie!'));
            }else{
                if($accepted == 0){
                    $thesisTopic->thesis_topic_status_id = 19; //Elutasítva
                    $thesisTopic->cause_of_rejecting_thesis_supplements = $this->getRequest()->getData('cause_of_rejecting_thesis_supplements');
                }else $thesisTopic->thesis_topic_status_id = 20; //Elfogadva
            }
            
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success($accepted == 0 ? __('Elutasítás sikeres.') : __('Elfogadás sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($accepted == 0 ? __('Elutasítás sikertelen.') : __('Elfogadás sikertelen.')) . ' ' . __('Próbálja újra!');
                
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
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
