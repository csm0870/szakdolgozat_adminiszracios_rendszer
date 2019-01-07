<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisTopics Model
 *
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property \App\Model\Table\YearsTable|\Cake\ORM\Association\BelongsTo $Years
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\FailedTopicSuggestionsTable|\Cake\ORM\Association\HasMany $FailedTopicSuggestions
 * @property \App\Model\Table\ThesesTable|\Cake\ORM\Association\HasMany $Theses
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

        $this->belongsTo('InternalConsultants', [
            'foreignKey' => 'internal_consultant_id'
        ]);
        $this->belongsTo('Years', [
            'foreignKey' => 'starting_year_id'
        ]);
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id'
        ]);
        $this->hasMany('FailedTopicSuggestions', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('Theses', [
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
            ->scalar('language')
            ->maxLength('language', 30)
            ->allowEmpty('language');

        $validator
            ->scalar('cause_of_no_external_consultant')
            ->allowEmpty('cause_of_no_external_consultant');

        $validator
            ->boolean('accepted_by_internal_consultant')
            ->allowEmpty('accepted_by_internal_consultant');

        $validator
            ->boolean('accepted_by_head_of_department')
            ->allowEmpty('accepted_by_head_of_department');

        $validator
            ->boolean('accepted_by_external_consultant')
            ->allowEmpty('accepted_by_external_consultant');

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
        $rules->add($rules->existsIn(['internal_consultant_id'], 'InternalConsultants'));
        $rules->add($rules->existsIn(['starting_year_id'], 'Years'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));

        return $rules;
    }
}
