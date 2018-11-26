<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Consultations Model
 *
 * @property \App\Model\Table\ThesesTable|\Cake\ORM\Association\BelongsTo $Theses
 * @property \App\Model\Table\ConsultationOccasionsTable|\Cake\ORM\Association\HasMany $ConsultationOccasions
 *
 * @method \App\Model\Entity\Consultation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Consultation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Consultation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Consultation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Consultation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Consultation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Consultation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Consultation findOrCreate($search, callable $callback = null, $options = [])
 */
class ConsultationsTable extends Table
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

        $this->setTable('consultations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Theses', [
            'foreignKey' => 'thesis_id'
        ]);
        $this->hasMany('ConsultationOccasions', [
            'foreignKey' => 'consultation_id'
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
            ->boolean('accepted')
            ->allowEmpty('accepted');

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
        $rules->add($rules->existsIn(['thesis_id'], 'Theses'));

        return $rules;
    }
}
