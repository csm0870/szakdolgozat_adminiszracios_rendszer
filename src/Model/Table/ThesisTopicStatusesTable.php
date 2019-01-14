<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisTopicStatuses Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\HasMany $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisTopicStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopicStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class ThesisTopicStatusesTable extends Table
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

        $this->setTable('thesis_topic_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ThesisTopics', [
            'foreignKey' => 'thesis_topic_status_id'
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
            ->maxLength('name', 60)
            ->allowEmpty('name');

        return $validator;
    }
}
