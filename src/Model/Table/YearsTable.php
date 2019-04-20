<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Years Model
 *
 * @property \App\Model\Table\FinalExamSubjectsTable|\Cake\ORM\Association\HasMany $FinalExamSubjects
 *
 * @method \App\Model\Entity\Year get($primaryKey, $options = [])
 * @method \App\Model\Entity\Year newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Year[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Year|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Year|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Year patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Year[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Year findOrCreate($search, callable $callback = null, $options = [])
 */
class YearsTable extends Table
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

        $this->setTable('years');
        $this->setDisplayField('year');
        $this->setPrimaryKey('id');

        $this->hasMany('FinalExamSubjects', [
            'foreignKey' => 'year_id'
        ]);
        $this->hasMany('StartingThesisTopics', [
            'foreignKey' => 'starting_year_id',
            'class' => 'ThesisTopics'
        ]);
        $this->hasMany('EndingThesisTopic', [
            'foreignKey' => 'accepted_ending_year_id',
            'class' => 'ThesisTopics'
        ]);
        $this->hasMany('FinalExamSubjects', [
            'foreignKey' => 'year_id'
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
            ->scalar('year')
            ->add('year', 'custom',['rule' => array('custom', '/[1-9][0-9]{3}\/[0-9]{1,2}/'),
                                    'message' => __('Nem megfelelő a tanév formátuma.')])
            ->requirePresence('year', 'create', __('Tanév megadása kötelező.'))
            ->notEmpty('year', __('Tanév megadása kötelező.'));

        return $validator;
    }
}
