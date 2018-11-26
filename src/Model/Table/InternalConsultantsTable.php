<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InternalConsultants Model
 *
 * @property \App\Model\Table\DepartmentsTable|\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\HasMany $ThesisTopics
 *
 * @method \App\Model\Entity\InternalConsultant get($primaryKey, $options = [])
 * @method \App\Model\Entity\InternalConsultant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InternalConsultant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalConsultant|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalConsultant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InternalConsultant findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InternalConsultantsTable extends Table
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

        $this->setTable('internal_consultants');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ThesisTopics', [
            'foreignKey' => 'internal_consultant_id'
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
            ->scalar('room_number')
            ->maxLength('room_number', 255)
            ->allowEmpty('room_number');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 255)
            ->allowEmpty('phone_number');

        $validator
            ->scalar('rank')
            ->maxLength('rank', 255)
            ->allowEmpty('rank');

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
        $rules->add($rules->existsIn(['department_id'], 'Departments'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
