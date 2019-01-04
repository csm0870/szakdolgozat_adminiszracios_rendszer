<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExternalConsultants Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\HasMany $ThesisTopics
 *
 * @method \App\Model\Entity\ExternalConsultant get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExternalConsultant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExternalConsultant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExternalConsultant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExternalConsultant|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExternalConsultant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExternalConsultant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExternalConsultant findOrCreate($search, callable $callback = null, $options = [])
 */
class ExternalConsultantsTable extends Table
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

        $this->setTable('external_consultants');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ThesisTopics', [
            'foreignKey' => 'external_consultant_id'
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
            ->scalar('workplace')
            ->maxLength('workplace', 50)
            ->allowEmpty('workplace');

        $validator
            ->scalar('position')
            ->maxLength('position', 50)
            ->allowEmpty('position');

        return $validator;
    }
}
