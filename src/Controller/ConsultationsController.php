<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Consultations Controller
 *
 * @property \App\Model\Table\ConsultationsTable $Consultations
 *
 * @method \App\Model\Entity\Consultation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConsultationsController extends AppController
{
    public function exportPdf($id = null){
        $consultation = $this->Consultations->find('all', ['conditions' => ['Consultations.id' => $id], 'contain' => ['ConsultationOccasions' => ['sort' => ['date' => 'ASC']]]])->first();
                
        $ok = true;
        
        $prefix = false;
        
        $group_id = $this->Auth->user('group_id');
        if($group_id == 1) // Admin
            $prefix = 'admin';
        elseif($group_id == 2) // Belső konzulens
            $prefix = 'internal_consultant';
        elseif($group_id == 3) // Tanszékvezető
            $prefix = 'head_of_department';
        elseif($group_id == 4) // Témakezelő
            $prefix = 'topic_manager';
        elseif($group_id == 5) // Szakdolgozatkezelő
            $prefix = 'thesis_manager';
        elseif($group_id == 6) // Hallgató
            $prefix = 'student';
        
        if(empty($consultation)){ //Konzultációs csoport
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('Konzultációs csoport nem létezik.'));
            $ok = false;
        }elseif($consultation->accepted === null){
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('Konzultációs csoport még nem véglegesített.'));
            $ok = false;
        }elseif(count($consultation->consultation_occasions) <= 0){
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('A konzultációs csoporthoz nincs egy alkalom sem hozzárendelve.'));
            $ok = false;
        }
        
        if($prefix == false) $this->Flash->error(__('Ismeretlen felhasználótípus. Ki lettél jelentkeztetve.'));
        
        if(!$ok) return $this->redirect($prefix === false ? ['controller' => 'Users', 'action' => 'logout'] : ['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        $thesisTopic = $this->Consultations->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $consultation->thesis_topic_id], 'contain' => ['ThesisTopicStatuses']])->first();

        if($this->Auth->user('group_id') == 2){ //Belső konzulens
            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        }
        
        if(empty($thesisTopic)){ //Nem létezik a téma
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('A téma, amelyhez tartozna, nem létezik.'));
            $ok = false;
        }elseif($this->Auth->user('group_id') == 2 && $thesisTopic->internal_consultant_id != ($user->has('internal_consultant') ? $user->internal_consultant->id : '')){ //Nem ehhez a belső konzulenshez tartozik
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('A témának, amelyhez tartozik, nem Ön a belső konzulense.'));
            $ok = false;
        }
        
        if($prefix == false) $this->Flash->error(__('Ismeretlen felhasználótípus. Ki lettél jelentkeztetve.'));
        
        if(!$ok) return $this->redirect($prefix === false ? ['controller' => 'Users', 'action' => 'logout'] : ['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        $internalConsultant = $this->Consultations->ThesisTopics->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $thesisTopic->internal_consultant_id], 'contain' => ['InternalConsultantPositions']])->first();
        if(empty($internalConsultant)){ //Nem létezik a téma
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('A téma, amelyhez tartozik, nincs belső konzulense.'));
            return $this->redirect(['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        }
        
        $student = $this->Consultations->ThesisTopics->Students->find('all', ['conditions' => ['Students.id' => $thesisTopic->student_id], 'contain' => ['Courses', 'CourseLevels']])->first();
        if(empty($student)){ //Nem létezik a hallgató
            $this->Flash->error(__('Nem exportálható PDF-be.') . ' ' . __('A téma, amelyhez tartozik, nem tartozik hozzá hallgató.'));
            if($prefix == false) $this->Flash->error(__('Ismeretlen felhasználótípus. Ki lettél jelentkeztetve.'));
            return $this->redirect($prefix === false ? ['controller' => 'Users', 'action' => 'logout'] : ['controller' => 'ThesisTopics', 'action' => 'index', 'prefix' => $prefix]);
        
        }
        
        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'title' => "konzultacios_lap-" . date("Y-m-d-H-i-s"),
                'margin' => [
                    'bottom' => 12,
                    'left' => 12,
                    'right' => 12,
                    'top' => 12
                ]
            ]
        ]);
        
        $this->set(compact('consultation', 'internalConsultant', 'student'));
    }
}