<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FinalExamSubjects Model
 *
 * @property \App\Model\Table\YearsTable|\Cake\ORM\Association\BelongsTo $Years
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\FinalExamSubject get($primaryKey, $options = [])
 * @method \App\Model\Entity\FinalExamSubject newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FinalExamSubject[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FinalExamSubject|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinalExamSubject|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinalExamSubject patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FinalExamSubject[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FinalExamSubject findOrCreate($search, callable $callback = null, $options = [])
 */
class FinalExamSubjectsTable extends Table
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

        $this->setTable('final_exam_subjects');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Years', [
            'foreignKey' => 'year_id'
        ]);
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id'
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
            ->notEmpty('name', __('Név megadása közelező.'));

        $validator
            ->scalar('teachers')
            ->notEmpty('teachers', __('Tanár(ok) megadása közelező.'));

        $validator
            ->boolean('semester')
            ->notEmpty('semester', __('Félév megadása közelező.'));

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
        $rules->add($rules->existsIn(['year_id'], 'Years'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));

        return $rules;
    }
}
