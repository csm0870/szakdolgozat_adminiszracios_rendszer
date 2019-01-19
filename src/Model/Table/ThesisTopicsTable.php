<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisTopics Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Years
 * @property |\Cake\ORM\Association\BelongsTo $Years
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property \App\Model\Table\LanguagesTable|\Cake\ORM\Association\BelongsTo $Languages
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ThesisTopicStatusesTable|\Cake\ORM\Association\BelongsTo $ThesisTopicStatuses
 * @property |\Cake\ORM\Association\HasMany $Consultations
 * @property \App\Model\Table\FailedTopicSuggestionsTable|\Cake\ORM\Association\HasMany $FailedTopicSuggestions
 * @property |\Cake\ORM\Association\HasMany $Reviews
 *
 * @method \App\Model\Entity\ThesisTopic get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThesisTopic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThesisTopic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopic|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThesisTopicsTable extends Table
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

        $this->setTable('thesis_topics');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StartingYears', [
            'foreignKey' => 'starting_year_id',
            'className' => 'Years'
        ]);
        $this->belongsTo('ExpectedEndingYears', [
            'foreignKey' => 'expected_ending_year_id',
            'className' => 'Years'
        ]);
        $this->belongsTo('InternalConsultants', [
            'foreignKey' => 'internal_consultant_id'
        ]);
        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id'
        ]);
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id'
        ]);
        $this->belongsTo('ThesisTopicStatuses', [
            'foreignKey' => 'thesis_topic_status_id'
        ]);
        $this->hasMany('Consultations', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('FailedTopicSuggestions', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'thesis_topic_id'
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('cause_of_no_external_consultant')
            ->allowEmpty('cause_of_no_external_consultant');

        $validator
            ->boolean('modifiable')
            ->allowEmpty('modifiable');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

        $validator
            ->boolean('is_thesis')
            ->allowEmpty('is_thesis');

        $validator
            ->boolean('encrypted')
            ->allowEmpty('encrypted');

        $validator
            ->boolean('starting_semester')
            ->allowEmpty('starting_semester');

        $validator
            ->boolean('expected_ending_semester')
            ->allowEmpty('expected_ending_semester');

        $validator
            ->scalar('external_consultant_name')
            ->maxLength('external_consultant_name', 50)
            ->allowEmpty('external_consultant_name');

        $validator
            ->scalar('external_consultant_workplace')
            ->maxLength('external_consultant_workplace', 50)
            ->allowEmpty('external_consultant_workplace');

        $validator
            ->scalar('external_consultant_position')
            ->maxLength('external_consultant_position', 50)
            ->allowEmpty('external_consultant_position');

        $validator
            ->scalar('external_consultant_email')
            ->maxLength('external_consultant_email', 60)
            ->allowEmpty('external_consultant_email');

        $validator
            ->scalar('external_consultant_phone_number')
            ->maxLength('external_consultant_phone_number', 60)
            ->allowEmpty('external_consultant_phone_number');

        $validator
            ->scalar('external_consultant_address')
            ->maxLength('external_consultant_address', 80)
            ->allowEmpty('external_consultant_address');

        $validator
            ->boolean('first_thesis_subject_completed')
            ->allowEmpty('first_thesis_subject_completed');

        $validator
            ->scalar('thesis_pdf')
            ->maxLength('thesis_pdf', 255)
            ->allowEmpty('thesis_pdf');

        $validator
            ->scalar('thesis_supplements')
            ->maxLength('thesis_supplements', 255)
            ->allowEmpty('thesis_supplements');

        $validator
            ->allowEmpty('internal_consultant_grade');

        $validator
            ->boolean('thesis_handed_in')
            ->allowEmpty('thesis_handed_in');

        $validator
            ->boolean('thesis_accepted')
            ->allowEmpty('thesis_accepted');

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
        $rules->add($rules->existsIn(['starting_year_id'], 'StartingYears'));
        $rules->add($rules->existsIn(['expected_ending_year_id'], 'ExpectedEndingYears'));
        $rules->add($rules->existsIn(['internal_consultant_id'], 'InternalConsultants'));
        $rules->add($rules->existsIn(['language_id'], 'Languages'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['thesis_topic_status_id'], 'ThesisTopicStatuses'));

        return $rules;
    }
}
