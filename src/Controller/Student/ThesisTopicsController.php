<?php
namespace App\Controller\Student;

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
     * 
     * @return type
     */
    public function index(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $can_fill_in_topic = false;
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();

        //Kitöltési időszak ellenőrzése
        if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
            $today = date('Y-m-d');

            $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
            $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

            if($today >= $start_date && $today <= $end_date){
                $can_fill_in_topic = true;
            }
        }

        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $data['student_id'], 'deleted !=' => true],
                                                          'order' => ['created' => 'DESC'],
                                                          'contain' => ['ThesisTopicStatuses']]);

        $can_add_topic = $this->ThesisTopics->Students->canAddTopic($data['student_id']);

        $this->set(compact('can_fill_in_topic', 'can_add_topic', 'thesisTopics'));
    }
    
    /**
     * Téma hozzáadása
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        if(!$this->ThesisTopics->Students->canAddTopic($data['student_id'])){
            $this->Flash->error(__('Nem adhat hozzá új témát!'));
            return $this->redirect(['action' => 'index']);
        }

        $can_fill_in_topic = false;
        $this->loadModel('Information');
        $info = $this->Information->find('all')->first();
        
        //Leadhatósági időszak ellenőrzése
        if(!empty($info) && !empty($info->filling_in_topic_form_begin_date) && !empty($info->filling_in_topic_form_end_date)){
            $today = date('Y-m-d');

            $start_date = $info->filling_in_topic_form_begin_date->i18nFormat('yyyy-MM-dd');
            $end_date = $info->filling_in_topic_form_end_date->i18nFormat('yyyy-MM-dd');

            if($today >= $start_date && $today <= $end_date){
                $can_fill_in_topic = true;
            }
        }

        if($can_fill_in_topic === true){
            $thesisTopic = $this->ThesisTopics->newEntity();
            if ($this->request->is('post')) {
                $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
                $thesisTopic->modifiable = true;
                $thesisTopic->student_id = $data['student_id'];
                $has_external_consultant = $this->getRequest()->getData('has_external_consultant');

                //Külső konzulensi mezők beállítása
                if(empty($has_external_consultant) || $has_external_consultant != 1){
                    $thesisTopic->external_consultant_name = null;
                    $thesisTopic->external_consultant_position = null;
                    $thesisTopic->external_consultant_workplace = null;
                    $thesisTopic->external_consultant_email = null;
                    $thesisTopic->external_consultant_phone_number = null;
                    $thesisTopic->external_consultant_address = null;
                }else{
                    $thesisTopic->cause_of_no_external_consultant = null;
                }
                
                //Véglegesítésre vár
                $thesisTopic->thesis_topic_status_id = 1;

                if ($this->ThesisTopics->save($thesisTopic)) {
                    $this->Flash->success(__('Mentés sikeres.'));

                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }

            $this->loadModel('Years');
            $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
            $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
            $languages = $this->ThesisTopics->Languages->find('list');
            $this->set(compact('thesisTopic', 'internalConsultants', 'years', 'languages'));
        }

        $this->set(compact('can_fill_in_topic'));
    }

    /**
     * Téma szerkesztése
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->get($id);

        if(!$thesisTopic->modifiable){
            $this->Flash->error(__('A téma nem módosítható!!'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
            $thesisTopic->student_id = $data['student_id'];
            $has_external_consultant = $this->getRequest()->getData('has_external_consultant');

            //Külső konzulensi mezők beállítása
            if(empty($has_external_consultant) || $has_external_consultant != 1){
                $thesisTopic->external_consultant_name = null;
                $thesisTopic->external_consultant_position = null;
                $thesisTopic->external_consultant_workplace = null;
                $thesisTopic->external_consultant_email = null;
                $thesisTopic->external_consultant_phone_number = null;
                $thesisTopic->external_consultant_address = null;
            }else{
                $thesisTopic->cause_of_no_external_consultant = null;
            }
            
            //Véglegesítésre vár
            $thesisTopic->thesis_topic_status_id = 1;

            if ($this->ThesisTopics->save($thesisTopic)) {
                $this->Flash->success(__('Mentés sikeres.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Hiba történt. Próbálja újra!'));
        }

        $this->loadModel('Years');
        $years = $this->Years->find('list', ['order' => ['year' => 'ASC']]);
        $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
        $languages = $this->ThesisTopics->Languages->find('list');
        $this->set(compact('thesisTopic', 'internalConsultants', 'years', 'languages'));
    }
    
    /**
     * Téma véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function finalize($id = null){
        //Hallgatói adatellenőrzés
        $this->loadModel('Students');
        $data = $this->Students->checkStundentData($this->Auth->user('id'));
        if($data['success'] === false){
            $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
            return $this->redirect(['controller' => 'Students', 'action' => 'edit', $data['student_id']]);
        }

        $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['id' => $id]])->first();
        if(empty($thesisTopic)){
            $this->Flash->error(__('Téma nem létezik.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $thesisTopic->modifiable = false;
        //Belső konzulensi döntésre vár
        $thesisTopic->thesis_topic_status_id = 2;

        if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
        else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
