<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Languages Controller
 *
 * @property \App\Model\Table\LanguagesTable $Languages
 *
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LanguagesController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['add', 'edit'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Képzésszintek listája
     */
    public function index(){
        $languages = $this->Languages->find('all');
        $this->set(compact('languages'));
    }
    
    /**
     * Nyelv hozzáadása
     */
    public function add(){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
                
        $saved = true;
        $error_ajax = "";
        
        $language = $this->Languages->newEntity();
        if($this->getRequest()->is('post')){
            $language = $this->Languages->patchEntity($language, $this->getRequest()->getData());
            if($this->Languages->save($language)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $language->getErrors();
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
        
        $this->set(compact('language', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Nyelv szerkesztése
     *
     * @param string|null $id Nyelv egyedi aznosítója
     */
    public function edit($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');

        $language = $this->Languages->find('all', ['conditions' => ['id' => $id]])->first();

        $error_msg = '';
        $ok = true;
        if(empty($language)){ //Ha nem létezik a képzésszint
            $error_msg = __('A kért nyelv nem létezik.');
            $ok = false;
            $this->set(compact('ok', 'error_msg'));
            return;
        }
        
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $language = $this->Languages->patchEntity($language, $this->getRequest()->getData());
            if($this->Languages->save($language)){
                $this->Flash->success(__('Mentés sikeres!'));
            }else{
                $saved = false;
                $error_ajax = __('Mentés sikertelen. Próbálja újra!');
                
                $errors = $language->getErrors();
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
        
        $this->set(compact('language', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }

    /**
     * Nyelv törlése
     *
     * @param string|null $id Nyelv egyedi aznosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $language = $this->Languages->find('all', ['conditions' => ['id' => $id]])->first();
        
        if(empty($language)){
            $this->Flash->error(__('Nyelv nem törölhető.') . ' ' . __('A nyelv nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        //Olyan témák, amelyek ezen a nyelven vannak és még folyamatban vannak
        $count_of_thesis_topics = $this->Languages->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.language_id' => $language->id,
                                                                                                'ThesisTopics.thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                                                                                                 \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]]])
                                   ->count(); 
                            
        if($count_of_thesis_topics > 0){
            $this->Flash->error(__('Nyelv nem törölhető.') . ' ' . __('Van folyamatban lévő téma ezen a nyelven.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if($this->Languages->delete($language)) $this->Flash->success(__('Törlés sikeres'));
        else $this->Flash->error(__('Törlés sikertelen. Kérjük próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
