<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FailedTopicSuggestions Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\BelongsTo $ThesisTopics
 *
 * @method \App\Model\Entity\FailedTopicSuggestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FailedTopicSuggestion findOrCreate($search, callable $callback = null, $options = [])
 */
class FailedTopicSuggestionsTable extends Table
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

        $this->setTable('failed_topic_suggestions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ThesisTopics', [
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
            ->scalar('suggestion')
            ->allowEmpty('suggestion');

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
        $rules->add($rules->existsIn(['thesis_topic_id'], 'ThesisTopics'));

        return $rules;
    }
}
