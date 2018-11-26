<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Information Model
 *
 * @method \App\Model\Entity\Information get($primaryKey, $options = [])
 * @method \App\Model\Entity\Information newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Information[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Information|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Information|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Information patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Information[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Information findOrCreate($search, callable $callback = null, $options = [])
 */
class InformationTable extends Table
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

        $this->setTable('information');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->date('filling_in_topic_form_begin_date')
            ->allowEmpty('filling_in_topic_form_begin_date');

        $validator
            ->date('filling_in_topic_form_end_date')
            ->allowEmpty('filling_in_topic_form_end_date');

        $validator
            ->scalar('encryption_requlation')
            ->allowEmpty('encryption_requlation');

        return $validator;
    }
}
