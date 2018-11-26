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
 * @property \App\Model\Table\ThesesTable|\Cake\ORM\Association\BelongsTo $Theses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FinalExamSubjectsTable|\Cake\ORM\Association\HasMany $FinalExamSubjects
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
        $this->belongsTo('Theses', [
            'foreignKey' => 'thesis_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('FinalExamSubjects', [
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmpty('name');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmpty('address');

        $validator
            ->scalar('neptun')
            ->maxLength('neptun', 255)
            ->allowEmpty('neptun')
            ->add('neptun', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 255)
            ->allowEmpty('phone_number');

        $validator
            ->scalar('specialisation')
            ->maxLength('specialisation', 255)
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
        $rules->add($rules->existsIn(['thesis_id'], 'Theses'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
