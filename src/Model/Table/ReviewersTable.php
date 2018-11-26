<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reviewers Model
 *
 * @property \App\Model\Table\ReviewsTable|\Cake\ORM\Association\HasMany $Reviews
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
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

        $this->hasMany('Reviews', [
            'foreignKey' => 'reviewer_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'reviewer_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_reviewers'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmpty('name');

        $validator
            ->scalar('workplace')
            ->maxLength('workplace', 255)
            ->allowEmpty('workplace');

        $validator
            ->scalar('position')
            ->maxLength('position', 255)
            ->allowEmpty('position');

        return $validator;
    }
}
