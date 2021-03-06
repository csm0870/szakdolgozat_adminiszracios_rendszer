<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CourseLevels Model
 *
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\HasMany $Students
 *
 * @method \App\Model\Entity\CourseLevel get($primaryKey, $options = [])
 * @method \App\Model\Entity\CourseLevel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CourseLevel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CourseLevel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseLevel|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseLevel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CourseLevel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CourseLevel findOrCreate($search, callable $callback = null, $options = [])
 */
class CourseLevelsTable extends Table
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

        $this->setTable('course_levels');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Students', [
            'foreignKey' => 'course_level_id'
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
            ->maxLength('name', 40, __('A név maximum 40 karakter lehet.'))
            ->requirePresence('name', 'create', __('Név megadása kötelező.'))
            ->notEmpty('name', __('Név megadása kötelező.'));

        return $validator;
    }
}
