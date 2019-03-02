<?php
namespace App\Controller\TopicManager;

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
     * Témakezelő témalista
     */
    public function index(){
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['InternalConsultants']]);
        //Csak a véglegesített témákat látja
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['deleted !=' => true, 'thesis_topic_status_id NOT IN' => [1, 2, 3, 4, 5]],
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
            
            if(empty($thesisTopic)){ //Nem létezik a téma
                $this->Flash->error(__('A téma elfogadásáról nem dönthet.') . ' ' . __('Nem létező téma.'));
                $ok = false;
            }elseif($thesisTopic->cause_of_no_external_consultant !== null){ //Nincs külső konzulens
                $this->Flash->error(__('A téma elfogadásáról nem dönthet.') . ' ' . __('A témának nincs kölső konzulense.'));
                $ok = false;
            }elseif($thesisTopic->thesis_topic_status_id != 10){ //Nem külső konzulensi aláírás ellenőrzésére vár
                $this->Flash->error(__('A téma elfogadásáról nem dönthet.') . ' ' . __('A téma nem külső konzulensi aláírás ellenőrzsére vár.'));
                return $this->redirect(['action' => 'index']);
            }
            
            if(!$ok) return $this->redirect(['action' => 'index']);
            
            //Elfogadás vagy elutasítás
            $thesisTopic->thesis_topic_status_id = $accepted == 0 ? 11 : 12;

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
        }elseif(in_array($thesisTopic->thesis_topic_status_id, [1, 2, 3, 4, 5])){ //Ha a téma még nincs véglegesítve
            $this->Flash->error(__('A téma részletei nem elérhetők.') . ' ' . __('A téma'). ' "' . ($thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : '') . '" státuszban van.' );
            $ok = false;
        }
        
        if(!$ok) return $this->redirect (['action' => 'index']);
        
        $this->set(compact('thesisTopic'));
    }
    
    public function statistics($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad  vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => date('Y')]])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        //Címkék
        $this->loadModel('Courses');
        $labels_for_courses_ = $this->Courses->find('list');
        $this->loadModel('CourseTypes');
        $labels_for_course_types_ = $this->CourseTypes->find('list');
        $this->loadModel('CourseLevels');
        $labels_for_course_levels_ = $this->CourseLevels->find('list');
        
        //Címkék sima tömbbe a diagramhoz
        $labels_for_courses = [];
        $labels_for_course_types = [];
        $labels_for_course_levels = [];
        
        //Diagramm adatok
        $data_for_courses = [];
        $data_for_course_types = [];
        $data_for_course_levels = [];
        
        //Képzésekhez tartozó témák számlálása
        foreach($labels_for_courses_ as $course_id => $course){
            $query = $this->ThesisTopics->find();
            $data_for_courses[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                 'ThesisTopics.starting_semester' => $semester,
                                                 'thesis_topic_status_id' => 12 /* Elfogadott*/])
                                        ->matching('Students', function ($q) use($course_id) { return $q->where(['Students.course_id' => $course_id]);})
                                        ->count();
            $labels_for_courses[] = $course;
        }
        
        //Képzési típusokhoz tartozó témák számlálása
        foreach($labels_for_course_types_ as $course_type_id => $course_type){
            $query = $this->ThesisTopics->find();
            $data_for_course_types[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                      'ThesisTopics.starting_semester' => $semester,
                                                      'ThesisTopics.thesis_topic_status_id' => 12 /* Elfogadott*/])
                                             ->matching('Students', function ($q) use($course_type_id) { return $q->where(['Students.course_type_id' => $course_type_id]);})
                                             ->count();
                                             
            $labels_for_course_types[] = $course_type;              
        }
        
        //Képzési szintekhez tartozó témák számlálása
        foreach($labels_for_course_levels_ as $course_level_id => $course_level){
            $query = $this->ThesisTopics->find();
            $data_for_course_levels[] = $query->where(['ThesisTopics.starting_year_id' => $year->id,
                                                       'ThesisTopics.starting_semester' => $semester,
                                                       'thesis_topic_status_id' => 12 /* Elfogadott*/])
                                               ->matching('Students', function ($q) use($course_level_id) { return $q->where(['Students.course_level_id' => $course_level_id]);})
                                               ->count();
            
            $labels_for_course_levels[] = $course_level;
        }
        
        $years = $this->Years->find('list');
        $this->set(compact('labels_for_courses', 'labels_for_course_types', 'labels_for_course_levels',
                           'data_for_courses', 'data_for_course_types', 'data_for_course_levels', 'years', 'year', 'semester'));
        
    }
    
    public function exports($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad  vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => '%' . date('Y') . '%']])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
            return $this->redirect(['action' => 'index']);
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        $years = $this->Years->find('list');
        
        $this->set(compact('year', 'semester', 'years'));
    }
    
    /**
     * CSV témalista adott év adott félévére
     * @param type $year_id
     * @param type $semester
     */
    public function exportCsv($year_id = null, $semester = 0){
        $this->loadModel('Years');
        $year = $this->Years->find('all', ['conditions' => ['id' => $year_id]])->first();
        
        //Ha paraméterben megadott év nem ad  vissza évet, akkor az aktuális évet lekérjük
        if(empty($year)) $year = $this->Years->find('all', ['conditions' => ['year LIKE' => '%' . date('Y') . '%']])->first();
        
        //Ha az aktuális év nem létezik, akkor az elsőt az adatbázisból
        if(empty($year)) $year = $this->Years->find('all')->first();
        
        if(empty($year)){
            $this->Flash->error(__('Nincs tanév az adatbázisban!'));
            return $this->redirect(['action' => 'exports']);
        }
        
        //Félév ellenőrzése
        $semester = in_array($semester, [0, 1]) ? $semester : 0;
        
        $headers = ['Neptun kód', 'Név', 'Belső konzulens', 'Téma címe', 'Szak', 'Képzés típusa', 'Képzés szintje', 'Tanév', 'Félév'];
        
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.starting_year_id' => $year->id, 'thesis_topic_status_id' => 12], //Elfogadott témák
                                                          'contain' => ['Students' => ['Courses', 'CourseTypes', 'CourseLevels'],
                                                                        'StartingYears', 'InternalConsultants']]);
        $data = [];
        foreach($thesisTopics as $thesisTopic){
            if($thesisTopic->has('student')){
                $data[] = [$thesisTopic->student->neptun, $thesisTopic->student->name,
                           $thesisTopic->has('internal_consultant') ? $thesisTopic->internal_consultant->name : '-',
                           $thesisTopic->title, $thesisTopic->student->has('course') ? $thesisTopic->student->course->name : '-',
                           $thesisTopic->student->has('course_type') ? $thesisTopic->student->course_type->name : '-',
                           $thesisTopic->student->has('course_level') ? $thesisTopic->student->course_level->name : '-',
                           $thesisTopic->has('starting_year') ? $thesisTopic->starting_year->year : '-', $thesisTopic->semester == 0 ? 'ősz' : 'tavasz'];
            }
        }
        
        $this->response->download("tema_adatok_" . str_replace('/', '_', $year->year) . "_" . $semester . '_' . date("Y-m-d-H-i-s") . '.csv');

        $_header = $headers;
        $_serialize = 'data';
        $_delimiter = "\t";
        $_dataEncoding = 'UTF-8';
        $_csvEncoding = 'UTF-16LE';
        $_bom = true;

        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('_header', '_serialize', 'data', '_delimiter', '_dataEncoding', '_csvEncoding', '_bom'));
    }
}
