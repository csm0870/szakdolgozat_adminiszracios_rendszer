<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * InternalConsultants Controller
 *
 * @property \App\Model\Table\InternalConsultantsTable $InternalConsultants
 *
 * @method \App\Model\Entity\InternalConsultant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InternalConsultantsController extends AppController
{

    /**
     * Belső konzulensek listája
     *
     * @return \Cake\Http\Response|void
     */
    public function index(){
        $internalConsultants = $this->InternalConsultants->find('all', ['contain' => ['InternalConsultantPositions', 'Departments']]);
        $this->set(compact('internalConsultants'));
    }
    
    /**
     * Belső konzulens részletek
     * 
     * @param type $id Belső konzulensi egyedi azonosítója
     */
    public function details($id = null){
        $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $id],
                                                                       'contain' => ['InternalConsultantPositions', 'Departments', 'Users' => ['RawPasswords']]])->first();
        
        if(empty($internalConsultant)){ //Nem létezik a belső konzulens
            $this->Flash->error(__('A belső konzulens részletei nem elérhetők.') . ' ' . __('Nem létező belső konzulens.'));
            return $this->redirect (['action' => 'index']);
        }
        
        $this->set(compact('internalConsultant'));
    }
    
    /**
     * Belső konzulens adatainak hozzáadása
     */
    public function add(){
        $internalConsultant = $this->InternalConsultants->newEntity();
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $internalConsultant = $this->InternalConsultants->patchEntity($internalConsultant, $this->request->getData());
            if ($this->InternalConsultants->save($internalConsultant)) {
                $this->Flash->success(__('Mentés sikeres.'));

                return $this->redirect(['action' => 'edit', $internalConsultant->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        $departments = $this->InternalConsultants->Departments->find('list', ['order' => ['Departments.name' => 'ASC']]);
        $internalConsultantPositions = $this->InternalConsultants->InternalConsultantPositions->find('list', ['order' => ['InternalConsultantPositions.name' => 'ASC']]);
        $this->set(compact('internalConsultant', 'departments', 'internalConsultantPositions'));
    }

    /**
     * Belső konzulens adatainak szerkesztése
     *
     * @param string|null $id Belső konzulensi egyedi azonosítója
     */
    public function edit($id = null){
        $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $id]])->first();
        
        if(empty($internalConsultant)){ //Nem létezik a belső konzulens
            $this->Flash->error(__('A belső konzulens adatai nem szerkeszthetőek.') . ' ' . __('Nem létező belső konzulens.'));
            return $this->redirect (['action' => 'index']);
        }
        
        if($this->getRequest()->is(['patch', 'post', 'put'])){
            $internalConsultant = $this->InternalConsultants->patchEntity($internalConsultant, $this->request->getData());
            if ($this->InternalConsultants->save($internalConsultant)) {
                $this->Flash->success(__('Mentés sikeres.'));

                return $this->redirect(['action' => 'edit', $internalConsultant->id]);
            }
            $this->Flash->error(__('Mentés sikertelen. Próbálja újra!'));
        }
        $departments = $this->InternalConsultants->Departments->find('list', ['order' => ['Departments.name' => 'ASC']]);
        $internalConsultantPositions = $this->InternalConsultants->InternalConsultantPositions->find('list', ['order' => ['InternalConsultantPositions.name' => 'ASC']]);
        $this->set(compact('internalConsultant', 'departments', 'internalConsultantPositions'));
    }

    /**
     * Belső konzulens törlése
     * 
     * @param type $id Belső konzulensi egyedi azonosítója
     */
    public function delete($id = null){
        $this->getRequest()->allowMethod(['post', 'delete']);
        $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $id],
                                                                       'contain' => ['ThesisTopics' => ['conditions' => ['ThesisTopics.deleted !=' => true, //Azon témák, amelyek folyamatban vannak
                                                                                                                         'ThesisTopics.thesis_topic_status_id NOT IN' => [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed'),
                                                                                                                                                                          \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')]]]]])->first();
        
        if(empty($internalConsultant)){ //Nem létezik a belső konzulens
            $this->Flash->error(__('A belső konzulens nem törölhető.') . ' ' . __('Nem létező belső konzulens.'));
            return $this->redirect (['action' => 'index']);
        }elseif(count($internalConsultant->thesis_topics) > 0){ //Van folyamatban lévő témája
            $this->Flash->error(__('A belső konzulens nem törölhető.') . ' ' . __('A belső konzulenshez tartozik folyamtban lévő téma.'));
            return $this->redirect (['action' => 'details', $internalConsultant->id]);
        }
        
        if($this->InternalConsultants->delete($internalConsultant)) $this->Flash->success(__('Törlés sikeres.'));
        else $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));

        return $this->redirect(['action' => 'index']);
    }
}
