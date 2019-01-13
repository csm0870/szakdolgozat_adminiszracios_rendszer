<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InternalConsultantPositions Model
 *
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\HasMany $InternalConsultants
 *
 * @method \App\Model\Entity\InternalConsultantPosition get($primaryKey, $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultantPosition findOrCreate($search, callable $callback = null, $options = [])
 */
class InternalConsultantPositionsTable extends Table
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

        $this->setTable('internal_consultant_positions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('InternalConsultants', [
            'foreignKey' => 'internal_consultant_position_id'
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
