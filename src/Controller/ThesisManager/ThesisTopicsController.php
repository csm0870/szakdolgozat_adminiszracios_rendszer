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
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id IN' => [18, 19, 20]],
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
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['ThesisSupplements', 'ThesisTopicStatuses', 'Students' => ['Courses', 'CourseLevels', 'CourseTypes'], 'InternalConsultants', 'StartingYears', 'ExpectedEndingYears', 'Languages']])->first();
        
        $ok = true;
        //Megnézzük, hogy megfelelő-e a téma a diplomamunka/szakdolgozat feltöltéséhez
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('Nem létezik a téma.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [18, 19, 20])){ //A szakdolgozati feltöltés nincs véglegesítve
            $this->Flash->error(__('Részeletek nem elérhetőek.') . ' ' . __('A szakdolgozat felöltése még nincs véglegesítve.'));
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

            if(isset($accepted) && !in_array($accepted, [0, 1])){
                $error_msg = __('Helytelen kérés. Próbálja újra!');
                $this->set(compact('ok', 'error_msg'));
                return;
            }
            
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? 19 : 20;
            $thesisTopic->cause_of_rejecting_thesis_supplements = $this->getRequest()->getData('cause_of_rejecting_thesis_supplements');
            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__(($accepted == 0 ? 'Elutasítás' : 'Elfogadás') . ' sikeres.'));
            }else{
                $saved = false;
                $error_ajax = ($accepted == 0 ? 'Elutasítás' : 'Elfogadás') . ' sikeretlen. Próbálja újra!';
                
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
