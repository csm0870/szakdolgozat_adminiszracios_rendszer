<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reviewers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ReviewsTable|\Cake\ORM\Association\HasMany $Reviews
 *
 * @method \App\Model\Entity\Reviewer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Reviewer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Reviewer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Reviewer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Reviewer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Reviewer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Reviewer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Reviewer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReviewersTable extends Table
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

        $this->setTable('reviewers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'reviewer_id'
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
            ->notEmpty('name', __('Név megadása kötelező.'))
            ->requirePresence('name', 'create', __('Név megadása kötelező.'));

        $validator
            ->email('email', false, __('Helytelen email formátum.'))
            ->notEmpty('email', __('Email megadása kötelező.'))
            ->requirePresence('email', 'create', __('Email megadása kötelező.'));

        $validator
            ->scalar('workplace')
            ->maxLength('workplace', 50)
            ->notEmpty('workplace', __('Munkahely megadása kötelező.'))
            ->requirePresence('workplace', 'create', __('Munkahely megadása kötelező.'));

        $validator
            ->scalar('position')
            ->maxLength('position', 255)
            ->notEmpty('position', __('Pozíció megadása kötelező.'))
            ->requirePresence('position', 'create', __('Pozíció megadása kötelező.'));

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
