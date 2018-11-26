<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reviews Model
 *
 * @property \App\Model\Table\ThesesTable|\Cake\ORM\Association\BelongsTo $Theses
 * @property \App\Model\Table\ReviewersTable|\Cake\ORM\Association\BelongsTo $Reviewers
 * @property \App\Model\Table\QuestionsTable|\Cake\ORM\Association\HasMany $Questions
 * @property \App\Model\Table\ThesesTable|\Cake\ORM\Association\HasMany $Theses
 *
 * @method \App\Model\Entity\Review get($primaryKey, $options = [])
 * @method \App\Model\Entity\Review newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Review[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Review|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Review|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Review patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Review[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Review findOrCreate($search, callable $callback = null, $options = [])
 */
class ReviewsTable extends Table
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

        $this->setTable('reviews');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Theses', [
            'foreignKey' => 'thesis_id'
        ]);
        $this->belongsTo('Reviewers', [
            'foreignKey' => 'reviewer_id'
        ]);
        $this->hasMany('Questions', [
            'foreignKey' => 'review_id'
        ]);
        $this->hasMany('Theses', [
            'foreignKey' => 'review_id'
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
            ->integer('structure_and_style_point')
            ->allowEmpty('structure_and_style_point');

        $validator
            ->scalar('cause_of_structure_and_style_point')
            ->allowEmpty('cause_of_structure_and_style_point');

        $validator
            ->integer('processing_literature_point')
            ->allowEmpty('processing_literature_point');

        $validator
            ->scalar('cause_of_processing_literature_point')
            ->allowEmpty('cause_of_processing_literature_point');

        $validator
            ->integer('writing_up_the_topic_point')
            ->allowEmpty('writing_up_the_topic_point');

        $validator
            ->scalar('cause_writing_up_the_topic_point')
            ->allowEmpty('cause_writing_up_the_topic_point');

        $validator
            ->integer('practical applicability_point')
            ->allowEmpty('practical applicability_point');

        $validator
            ->scalar('cause_of_practical applicability')
            ->allowEmpty('cause_of_practical applicability');

        $validator
            ->scalar('general_comments')
            ->allowEmpty('general_comments');

        $validator
            ->integer('grade')
            ->allowEmpty('grade');

        $validator
            ->scalar('confidentiality_contract')
            ->maxLength('confidentiality_contract', 255)
            ->allowEmpty('confidentiality_contract');

        $validator
            ->boolean('confidentiality_contract_accepted')
            ->allowEmpty('confidentiality_contract_accepted');

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
        $rules->add($rules->existsIn(['reviewer_id'], 'Reviewers'));

        return $rules;
    }
}
