<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisTopics Model
 *
 * @property \App\Model\Table\ExternalConsultantsTable|\Cake\ORM\Association\BelongsTo $ExternalConsultants
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property \App\Model\Table\ThesisTypesTable|\Cake\ORM\Association\BelongsTo $ThesisTypes
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

        $this->belongsTo('ExternalConsultants', [
            'foreignKey' => 'external_consultant_id'
        ]);
        $this->belongsTo('InternalConsultants', [
            'foreignKey' => 'internal_consultant_id'
        ]);
        $this->belongsTo('ThesisTypes', [
            'foreignKey' => 'thesis_type_id'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('starting_semester')
            ->maxLength('starting_semester', 255)
            ->allowEmpty('starting_semester');

        $validator
            ->scalar('language')
            ->maxLength('language', 255)
            ->allowEmpty('language');

        $validator
            ->scalar('cause_of_no_external_consultant')
            ->allowEmpty('cause_of_no_external_consultant');

        $validator
            ->dateTime('modeified')
            ->allowEmpty('modeified');

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
            ->boolean('encrytped')
            ->allowEmpty('encrytped');

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
        $rules->add($rules->existsIn(['external_consultant_id'], 'ExternalConsultants'));
        $rules->add($rules->existsIn(['internal_consultant_id'], 'InternalConsultants'));
        $rules->add($rules->existsIn(['thesis_type_id'], 'ThesisTypes'));

        return $rules;
    }
}
