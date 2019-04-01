<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Students Model
 *
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 * @property \App\Model\Table\CourseLevelsTable|\Cake\ORM\Association\BelongsTo $CourseLevels
 * @property \App\Model\Table\CourseTypesTable|\Cake\ORM\Association\BelongsTo $CourseTypes
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property \App\Model\Table\FinalExamSubjectsTable|\Cake\ORM\Association\HasMany $FinalExamSubjects
 * @property \App\Model\Table\OfferedTopicsTable|\Cake\ORM\Association\HasMany $OfferedTopics
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\HasMany $ThesisTopics
 *
 * @method \App\Model\Entity\Student get($primaryKey, $options = [])
 * @method \App\Model\Entity\Student newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Student[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Student|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Student|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Student patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Student[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Student findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StudentsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('students');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id'
        ]);
        $this->belongsTo('CourseLevels', [
            'foreignKey' => 'course_level_id'
        ]);
        $this->belongsTo('CourseTypes', [
            'foreignKey' => 'course_type_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('FinalExamSubjectsInternalConsultants', [
            'foreignKey' => 'final_exam_subjects_internal_consultant_id',
            'className' => 'InternalConsultants'
        ]);
        $this->hasMany('FinalExamSubjects', [
            'foreignKey' => 'student_id'
        ]);
        $this->hasMany('OfferedTopics', [
            'foreignKey' => 'student_id'
        ]);
        $this->hasMany('ThesisTopics', [
            'foreignKey' => 'student_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->allowEmpty('name');

        $validator
            ->scalar('address')
            ->maxLength('address', 80)
            ->allowEmpty('address');

        $validator
            ->scalar('neptun')
            ->maxLength('neptun', 6)
            ->allowEmpty('neptun')
            ->add('neptun', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 15)
            ->allowEmpty('phone_number');

        $validator
            ->scalar('specialisation')
            ->maxLength('specialisation', 40)
            ->allowEmpty('specialisation');

        $validator
            ->allowEmpty('final_exam_subjects_status');
        
        $validator
            ->boolean('passed_final_exam')
            ->allowEmpty('passed_final_exam');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['neptun']));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        $rules->add($rules->existsIn(['course_level_id'], 'CourseLevels'));
        $rules->add($rules->existsIn(['course_type_id'], 'CourseTypes'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['final_exam_subjects_internal_consultant_id'], 'FinalExamSubjectsInternalConsultants'));

        return $rules;
    }
    
        /**
     * Hallgató adatinak ellenőrzése. Ha még nincs hozzárendelve rekord, vagy ha valamely kötelező adata hiányzik, akkor false-t ad vissza.
     * Ha nincs hozzárendelve rekord, akkor létrehozza.
     * 
     * @param integer $user_id Felhasználói azonosíto
     * @return array ['success' => 'boolean' , 'student_id' => 'integer'] "success" tag megmondja, hogy megfeletek-e az adatok, "student_id" tag a adatokhoz tartozó ID-t adja meg
     */
    public function checkStundentData($user_id = null){
        $student = $this->find('all', ['conditions' => ['user_id' => $user_id]])->first();
            
        //Ha még nincs a hallgatói userhez hallgató rendelve, akkor létrehozzuk
        if(empty($student)){
            $student = $this->newEntity();
            $student->user_id = $user_id;
            if(!$this->save($student)){
                throw new \Cake\Core\Exception\Exception(__('Hiba történt. Próbálja újra!'));
}
            return ['success' => false, 'student_id' => $student->id];
        }

        if(empty($student->name) || empty($student->email) || empty($student->neptun) || empty($student->email) || empty($student->phone_number) ||
           empty($student->course_id) || empty($student->course_level_id) || empty($student->course_type_id)){
            return ['success' => false, 'student_id' => $student->id];
        }
        
        return ['success' => true, 'student_id' => $student->id];
    }
    
    /**
     * Megnézi, hogy az adott hallgató adhat-e le új témát
     * 
     * @param type $student_id Hallgató azonosítója
     * @return boolean Adhat-e hozzá témát
     */
    public function canAddTopic($student_id = null){
        if(empty($student_id)) return false;
        
        if(!$this->exists(['id' => $student_id])) return false;
        
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $student_id, 'deleted' => false], 'order' => ['created' => 'ASC']]);
            
        $can_add_topic = true;
        foreach($thesisTopics as $thesisTopic){
            //Ha csak elutasított témája van
            if(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed')])){
    $can_add_topic = false;
                break;
            }
        }
        
        return $can_add_topic;
    }
    
    /**
     * Megnézi, hogy az adott hallgató módosíthatja-e az adatait
     * 
     * @param type $student_id Hallgató azonosítója
     * @return boolean Módosíthatja-e az adatait
     */
    public function canModifyData($student_id = null){
        if(empty($student_id)) return false;
        
        if(!$this->exists(['id' => $student_id])) return false;
        
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $student_id, 'deleted' => false], 'order' => ['created' => 'ASC']]);
            
        $can_modify_data = true;
        foreach($thesisTopics as $thesisTopic){
            //Ha csak elutasított témája van vagy véglegesítésre váró
            if(!in_array($thesisTopic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant'),
                                                                \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed')])){
                $can_modify_data = false;
                break;
            }
        }
        
        return $can_modify_data;
    }
    
    /**
     * Mentés előtti callback
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function beforeSave($event, $entity, $options){
        //======================================================================
        // ÁLLAPOTVÁLTOZÁSOKKOR AZ EGYES ADATOK RESETELÉSE (eleje)
        //======================================================================
        
        //Ha a belső konzulens elfogadta a ZV-tárgyakat, és mérnökinformatikus, és Ha már van elfogadott témája és fel vannak vive az adatai a Neptun rendszerbe, vagyis már mehet ZV-ra
        if($entity->getOriginal('final_exam_subjects_status') == 2 && $entity->final_exam_subjects_status == 3 && $entity->course_id == 1 &&
           $this->ThesisTopics->exists(['student_id' => $entity->id,
                                        'thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted'),
                                        'accepted_thesis_data_applyed_to_neptun' => true])){
            
                $entity->passed_final_exam = false;
        }
        
        //======================================================================
        // ÁLLAPOTVÁLTOZÁSOKKOR AZ EGYES ADATOK RESETELÉSE (vége)
        //======================================================================
    }
    
    /**
     * Mentés után callback
     * 
     * Értesítések létrehozása
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function afterSave($event, $entity, $options){
        //======================================================================
        // ÉRTESÍTÉSEK KEZDETE
        //======================================================================
        
        if($entity->isNew() == false){
            //Ha a halllgató véglegesíti
            if($entity->getOriginal('final_exam_subjects_status') == 1 && $entity->final_exam_subjects_status == 2){
                
                $internalConsultant = $this->ThesisTopics->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->final_exam_subjects_internal_consultant_id],
                                                                                             'contain' => ['Users']])->first();
                
                if(!empty($internalConsultant) && $internalConsultant->has('user')){
                    $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                    
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $internalConsultant->user_id;
                    $notification->unread = true;
                    $notification->subject = 'Egy hallgató záróvizsga-tárgy javaslatokat adott meg. Ellenőrizze a megfelelőségüket!';
                    $notification->message = 'A ' . h($entity->name) . ' (' . h($entity->neptun) . ') nevű hallgató megadta a záróvizsga-tárgy javaslatait.' .
                                             '<br/><a href="' . \Cake\Routing\Router::url(['controller' => 'FinalExamSubjects', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                    $Notifications->save($notification);
                }
            }
            
            //Ha a belső konzulens elfogadta a ZV-tárgyakat
            if($entity->getOriginal('final_exam_subjects_status') == 2 && $entity->final_exam_subjects_status == 3){
                
                $user = $this->Users->find('all', ['conditions' => ['Users.id' => $entity->user_id]])->first();
                $internalConsultant = $this->ThesisTopics->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->final_exam_subjects_internal_consultant_id]])->first();
                
                if(!empty($user) && !empty($internalConsultant)){
                    $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                    
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $user->id;
                    $notification->unread = true;
                    $notification->subject = 'A záróvizsga-tárgyakat elfogadta a belső konzulense.';
                    $notification->message = 'A ' . h($internalConsultant->name) . ' nevű belső konzulense elfogadta a záróvizsga-tárgyakat.' .
                                             '<br/><a href="' . \Cake\Routing\Router::url(['controller' => 'FinalExamSubjects', 'action' => 'index', 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                    $Notifications->save($notification);
                    
                    //Ha már van elfogadott témája és fel vannak vive az adatai a Neptun rendszerbe, akkor már mehet ZV-ra
                    if($this->ThesisTopics->exists(['student_id' => $entity->id,
                                                    'thesis_topic_status_id' => \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted'),
                                                    'accepted_thesis_data_applyed_to_neptun' => true])){
                        $student = $this->get($entity->id, ['contain' => ['Courses', 'CourseTypes', 'CourseLevels']]);
                        //Záróvizsga összeállítók
                        $final_exam_organizers = $this->Users->find('all', ['conditions' => ['group_id' => 8]]);
                        foreach($final_exam_organizers as $final_exam_organizer){
                            $notification = $Notifications->newEntity();
                            $notification->user_id = $final_exam_organizer->id;
                            $notification->unread = true;
                            $notification->subject = 'Egy hallgató megfelelt a záróvizsga követelményeknek. Záróvizsgára mehet.';
                            $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                                     ($student->has('course') ? 'Szak: ' . h($student->course->name) . '<br/>' : '') .
                                                     ($student->has('course_type') ? 'Tagozat: ' . h($student->course_type->name) . '<br/>' : '') .
                                                     ($student->has('course_level') ? 'Képzési szint: ' . h($student->course_level->name) . '<br/>' : '') .
                                                     'Ez az üzenet nem jelenti azt, hogy a hallgató jelentkezett már záróvizsgára.' . '<br/>';
                            $Notifications->save($notification);
                        }
                    }
                }
            }
        }
        
        //======================================================================
        // ÉRTESÍTÉSEK VÉGE
        //======================================================================
    }
}
