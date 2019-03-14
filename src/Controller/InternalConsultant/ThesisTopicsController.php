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
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        if(in_array($this->getRequest()->getParam('action'), ['setFirstThesisSubjectCompleted', 'setThesisGrade'])) $this->viewBuilder()->setLayout(false);
    }
    
    /**
     * Belső konzulenshez tartozó témák listája
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak a véglegesített és a hozzá tartozó témákat látja
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['internal_consultant_id' => ($user->has('internal_consultant') ? $user->internal_consultant->id : ''),
                                                                           'deleted !=' => true, 'thesis_topic_status_id NOT IN' => [1, 2, 3, 4, 5] /* Még nincs leadva */],
                                                          'contain' => ['Students', 'ThesisTopicStatuses'], 'order' => ['ThesisTopics.modified' => 'DESC']]);

        $this->set(compact('thesisTopics'));
    }

    /**
     * Téma törlése a belső konzulens által (nem tényleges fizikai törlés)
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['ThesisTopicStatuses']])->first();
    
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [7, 9, 11, 12, 14, 15, 16, 17, 18, 19, 20, 23, 24, 25])){
            $this->Flash->error(__('A témát nem törölheti.') . ' ' . __('A bírálata még folyamatban van.'));
            return $this->redirect(['action' => 'index']);
        }

        if(!$ok) return $this->redirect(['action' => 'index']);

        $thesisTopic->deleted = true;
        if ($this->ThesisTopics->save($thesisTopic)) {
            $this->Flash->success(__('Törlés sikeres.'));
        } else {
            $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Téma részletek
     * 
     * @param type $id
     */
    public function details($id = null){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id],
                                                         'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                                      'ThesisSupplements' ,'ThesisTopicStatuses', 'StartingYears', 'ExpectedEndingYears', 'Languages',
                                                                                      'Reviews' => ['Reviewers']]])->first();
    
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('Nem létező téma.'));
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A témának nem Ön a belső konzulense.'));
            $ok = false;
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [1, 2, 3, 4, 5])){ //Nem "A téma nem elfogadott", nem "Diplomakurzus sikertelen, tanaszékvezető döntésére vár", nem "Első diplomakurzus teljesítve", vagy nem "Elutsítva (első diplomakurzus sikertelen)" státuszban van
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.');
            $ok = false;
        }
        
        if(!$ok) return $this->redirect (['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
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

            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $thesisTopic_id], 'contain' => ['ThesisTopicStatuses']])->first();

            $ok = true;
            
            if(empty($thesisTopic)){ //Nem létezik a téma
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('Nem létező téma.'));
            }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('A témának nem Ön a belső konzulense.'));
            }elseif($thesisTopic->thesis_topic_status_id != 6){ //Nem "A téma belső konzulensi döntésre vár" státuszban van
                $ok = false;
                $this->Flash->error(__('A témáról nem dönthet.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.');
            }
            
            if($ok === false) return $this->redirect(['action' => 'index']);
            
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? 7 : 8;

            if($this->ThesisTopics->save($thesisTopic)){
                $this->Flash->success(__($accepted == 0 ? 'Elutasítás sikeres.' : 'Elfogadás sikeres.'));
            }else{
                $this->Flash->error(__(($accepted == 0 ? 'Elutasítás sikeretlen.' : 'Elfogadás sikeretlen.') . 'Próbálja újra!'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Diplomakurzus első félévének teljesítésének rögzítése
     * 
     * @param type $id Téma azonosítója
     */
    public function setFirstThesisSubjectCompleted($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['ThesisTopicStatuses']])->first();

        $error_msg = '';
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('Nem létező téma.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('A témának nem Ön a belső konzulense.');
            $ok = false;
        }elseif($thesisTopic->thesis_topic_status_id != 12){ //Nem "A téma elfogadott" státuszban van
            $error_msg = __('A diplomakurzus első félévének teljesítésének rögzítését nem teheti meg.') . ' ' . __('A téma nem elfogadott státuszban van.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            $first_thesis_subject_completed = $this->getRequest()->getData('first_thesis_subject_completed');
            
            if($first_thesis_subject_completed === null || !in_array($first_thesis_subject_completed, [0, 1])){
                $thesisTopic->setError('custom', __('A döntésnek "0"(nem) vagy "1"(igen) értéket kell felvennie!'));
            }else{
                if($first_thesis_subject_completed == 0){ //Első diplomakurzus sikertelen
                    $thesisTopic->thesis_topic_status_id = 13; //Téma elutasítva (első diplomakurzus sikertelen)
                    $thesisTopic->first_thesis_subject_failed_suggestion = $this->getRequest()->getData('first_thesis_subject_failed_suggestion'); //Elutasítás oka
                }else{ //Első diplomakurzus sikeres
                    $thesisTopic->thesis_topic_status_id = 15; //Első diplomakurzus teljesítve
                    $thesisTopic->first_thesis_subject_failed_suggestion = null; // Javaslat nullázása
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
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
    
    /**
     * Dolgozat értékelése
     * 
     * @param type $id Téma azonosítója
     */
    public function setThesisGrade($id = null){
        $this->getRequest()->allowMethod('ajax');
        $this->viewBuilder()->setClassName('Ajax.Ajax');
        
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $id], 'contain' => ['ThesisTopicStatuses']])->first();

        $error_msg = '';
        $ok = true;
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('Nem létező dolgozat.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : -1)){ ////Nem ehhez a belső konzulenshez tartozik
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('A dolgozatnak nem Ön a belső konzulense.');
            $ok = false;
        }elseif(!in_array($thesisTopic->thesis_topic_status_id, [16, 17, 18, 19, 20, 21, 22, 23, 24])){ //Nincs legalább "Formai követelményeknek megfelelt" státuszban
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('A dolgozat még nincs abban az állapotban, hogy értékelhető legyen.');
            $ok = false;
        }elseif($thesisTopic->internal_consultant_grade !== null){ ////Már értékelve van
            $error_msg = __('A dolgozat értékelését nem teheti meg.') . ' ' . __('A dolgozat már értékelve van.');
            $ok = false;
        }
        
        //Ha a feltételeknek megfelelő téma nem található
        if($ok === false){
            $this->set(compact('ok', 'error_msg'));
            return;
        }
                
        $saved = true;
        $error_ajax = "";
        if($this->getRequest()->is(['post', 'patch', 'put'])){
            
            $internal_consultant_grade = $this->getRequest()->getData('internal_consultant_grade');
            
            if(isset($internal_consultant_grade)){
                $thesisTopic->internal_consultant_grade = $internal_consultant_grade;
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
            }else{
                $saved = false;
                $error_ajax = __('Értékelés megadása kötelező.');
            }
        }
        
        $this->set(compact('thesisTopic' ,'ok', 'error_msg', 'saved', 'error_ajax'));
        $this->set('_serialize', ['saved', 'error_ajax']);
    }
}
