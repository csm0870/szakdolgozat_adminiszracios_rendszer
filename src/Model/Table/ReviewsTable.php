<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reviews Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\BelongsTo $ThesisTopics
 * @property \App\Model\Table\ReviewersTable|\Cake\ORM\Association\BelongsTo $Reviewers
 * @property \App\Model\Table\QuestionsTable|\Cake\ORM\Association\HasMany $Questions
 *
 * @method \App\Model\Entity\Review get($primaryKey, $options = [])
 * @method \App\Model\Entity\Review newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Review[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Review|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Review|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Review patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Review[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Review findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
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

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', ['confidentiality_contract' => ['path' =>'files{DS}confidentiality_contracts{DS}']]);

        $this->belongsTo('ThesisTopics', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->belongsTo('Reviewers', [
            'foreignKey' => 'reviewer_id'
        ]);
        $this->hasMany('Questions', [
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
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('structure_and_style_point');

        $validator
            ->scalar('cause_of_structure_and_style_point')
            ->allowEmpty('cause_of_structure_and_style_point');

        $validator
            ->allowEmpty('processing_literature_point');

        $validator
            ->scalar('cause_of_processing_literature_point')
            ->allowEmpty('cause_of_processing_literature_point');

        $validator
            ->allowEmpty('writing_up_the_topic_point');

        $validator
            ->scalar('cause_writing_up_the_topic_point')
            ->allowEmpty('cause_writing_up_the_topic_point');

        $validator
            ->allowEmpty('practical_applicability_point');

        $validator
            ->scalar('cause_of_practical_applicability')
            ->allowEmpty('cause_of_practical_applicability');

        $validator
            ->scalar('general_comments')
            ->allowEmpty('general_comments');

        $validator
            ->allowEmpty('grade');

        $validator
            ->allowEmpty('confidentiality_contract')
            ->add('confidentiality_contract', 'custom', [ //Csak PDF lehet a fájlformátum
                    'rule' => function($value, $context){
                        if(!empty($value) && !empty($value['name'])){
                            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
                            if($ext != 'pdf'){
                                return false;
                            }
                        }
                        
                        return true;
                    }, 
                    'message' => __('Csak PDF a megengedett fájl formátum.')
                ]);

        $validator
            ->allowEmpty('confidentiality_contract_status');

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
        $rules->add($rules->existsIn(['thesis_topic_id'], 'ThesisTopics'));
        $rules->add($rules->existsIn(['reviewer_id'], 'Reviewers'));

        return $rules;
    }
}
