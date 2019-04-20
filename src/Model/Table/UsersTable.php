<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\HasMany $InternalConsultants
 * @property \App\Model\Table\NotificationsTable|\Cake\ORM\Association\HasMany $Notifications
 * @property \App\Model\Table\RawPasswordsTable|\Cake\ORM\Association\HasMany $RawPasswords
 * @property \App\Model\Table\ReviewersTable|\Cake\ORM\Association\HasMany $Reviewers
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\HasMany $Students
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Acl.Acl', ['type' => 'requester']);

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasOne('InternalConsultants', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Notifications', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('RawPasswords', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Reviewers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Students', [
            'foreignKey' => 'user_id'
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
            ->email('email', false, __('Nem megfelelő email formátum.'))
            ->notEmpty('email', __('Felhasználónév megadása kötelező.'))
            ->requirePresence('email', 'create', __('Felhasználónév megadása kötelező.'));

        $validator
            ->scalar('password')
            ->maxLength('password', 255, __('A jelszó maximum 255 karakter lehet.'))
            ->notEmpty('password', __('Jelszó megadása kötelező.'))
            ->requirePresence('password', 'create', __('Jelszó megadása kötelező.'));
        
        $validator
            ->notEmpty('group_id', __('Felhasználói csoport megadása kötelező.'))
            ->requirePresence('group_id', 'create', __('Felhasználói csoport megadása kötelező.'));

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
        $rules->add($rules->isUnique(['email'], __('Ez az érték már létezik.')));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }
}
