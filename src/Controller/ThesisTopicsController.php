<?php
namespace App\Controller;

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
     * Hallgatói témalista
     * 
     * @return type
     */
    public function studentIndex(){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
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
            
            $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $data['student_id'], 'deleted' => false], 'order' => ['created' => 'ASC']]);
            
            $can_add_topic = $this->ThesisTopics->Students->canAddTopic($data['student_id']);
                        
            $this->set(compact('can_fill_in_topic', 'can_add_topic', 'thesisTopics'));
        }
    }
    
    /**
     * Hallgatói hozzáadás
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function studentAdd()
    {
        
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            if(!$this->ThesisTopics->Students->canAddTopic($data['student_id'])){
                $this->Flash->error(__('Nem adhat hozzá új témát!'));
                return $this->redirect(['action' => 'studentIndex']);
            }
            
            $can_fill_in_topic = false;
            $this->loadModel('Information');
            $info = $this->Information->find('all')->first();

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
                    }else{
                        $thesisTopic->cause_of_no_external_consultant = null;
                    }

                    if ($this->ThesisTopics->save($thesisTopic)) {
                        $this->Flash->success(__('Mentés sikeres.'));

                        return $this->redirect(['action' => 'studentIndex']);
                    }
                    
                    $this->Flash->error(__('Hiba történt. Próbálja újra!'));
                }

                $years = $this->ThesisTopics->Years->find('list', ['order' => ['year' => 'ASC']]);
                $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
                $this->set(compact('thesisTopic', 'internalConsultants', 'years'));
            }
        
            $this->set(compact('can_fill_in_topic'));
        }
    }

    /**
     * Hallgatói szerkesztés
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function studentEdit($id = null)
    {
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            $thesisTopic = $this->ThesisTopics->get($id);
            
            if(!$thesisTopic->modifiable){
                $this->Flash->error(__('A téma nem módosítható!!'));
                return $this->redirect(['action' => 'studentIndex']);
            }
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $thesisTopic = $this->ThesisTopics->patchEntity($thesisTopic, $this->request->getData());
                $thesisTopic->modifiable = true;
                $thesisTopic->student_id = $data['student_id'];
                $has_external_consultant = $this->getRequest()->getData('has_external_consultant');

                //Külső konzulensi mezők beállítása
                if(empty($has_external_consultant) || $has_external_consultant != 1){
                    $thesisTopic->external_consultant_name = null;
                    $thesisTopic->external_consultant_position = null;
                    $thesisTopic->external_consultant_workplace = null;
                }else{
                    $thesisTopic->cause_of_no_external_consultant = null;
                }

                if ($this->ThesisTopics->save($thesisTopic)) {
                    $this->Flash->success(__('Mentés sikeres.'));

                    return $this->redirect(['action' => 'studentIndex']);
                }

                $this->Flash->error(__('Hiba történt. Próbálja újra!'));
            }

            $years = $this->ThesisTopics->Years->find('list', ['order' => ['year' => 'ASC']]);
            $internalConsultants = $this->ThesisTopics->InternalConsultants->find('list');
            $this->set(compact('thesisTopic', 'internalConsultants', 'years'));
        }
    }
    
    /**
     * Hallgatói véglegesítés
     * 
     * @param type $id Téma ID-ja
     * @return type
     */
    public function studentFinalize($id = null){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
            
            $thesisTopic = $this->ThesisTopics->get($id);
            $thesisTopic->modifiable = false;

            if ($this->ThesisTopics->save($thesisTopic)) $this->Flash->success(__('Véglegesítve'));
            else $this->Flash->error(__('Hiba történt. Próbálja újra!'));

            return $this->redirect(['action' => 'studentIndex']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Thesis Topic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $thesisTopic = $this->ThesisTopics->get($id);
        if ($this->ThesisTopics->delete($thesisTopic)) {
            $this->Flash->success(__('The thesis topic has been deleted.'));
        } else {
            $this->Flash->error(__('The thesis topic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function exportPdf($id = null){
        if($this->Auth->user('group_id') == 6){
            //Hallgatói adatellenőrzés
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
        }
        
        $thesisTopic = $this->ThesisTopics->get($id, ['contain' => ['Students' => ['Courses', 'CourseLevels', 'CourseTypes'], 'InternalConsultants' => ['Departments'], 'Years']]);
        
        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'title' => "feladatkiiro_lap-" . date("Y-m-d-H-i-s"),
                'margin' => [
                    'bottom' => 14,
                    'left' => 14,
                    'right' => 14,
                    'top' => 14
                ]
            ]
        ]);

        $this->set(compact('thesisTopic'));
    }
}
