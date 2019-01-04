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
 * @property \App\Model\Table\FinalExamSubjectsTable|\Cake\ORM\Association\HasMany $FinalExamSubjects
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
        $this->hasMany('FinalExamSubjects', [
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
            ->boolean('first_thesis_subject_completed')
            ->allowEmpty('first_thesis_subject_completed');

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
     * @param type $student_id
     * @return boolean Adhat-e hozzá témát
     */
    public function canAddTopic($student_id = null){
        if(empty($student_id)) return false;
        
        if(!$this->exists(['id' => $student_id])) return false;
        
        $thesisTopics = $this->ThesisTopics->find('all', ['conditions' => ['student_id' => $student_id, 'deleted' => false], 'order' => ['created' => 'ASC']]);
            
        $can_add_topic = true;
        foreach($thesisTopics as $thesisTopic){
            //Ha van külső konzulens, és már kiderült, hogy elfogadta-e vagy sem
            if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->accepted_by_external_consultant !== null){
                if($thesisTopic->accepted_by_external_consultant == true){
                    $can_add_topic = false;
                }
            }elseif($thesisTopic->accepted_by_head_of_department !== null){//Ha már a tanszékvezető döntött
                if($thesisTopic->accepted_by_head_of_department == true){
                    $can_add_topic = false;
                }
            }elseif($thesisTopic->accepted_by_internal_consultant !== null){//Ha már a tanszékvezető döntött
                if($thesisTopic->accepted_by_internal_consultant == true){
                    $can_add_topic = false;
                }
            }else{
                $can_add_topic = false;
            }
            //Ha legalább egy olyan téma van, amely vagy folyamatban van, vagy már el van fogadva
            if($can_add_topic === false) break;
        }
        
        return $can_add_topic;
    }
}
