<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Theses Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\BelongsTo $ThesisTopics
 * @property \App\Model\Table\ConsultationsTable|\Cake\ORM\Association\HasMany $Consultations
 * @property \App\Model\Table\ReviewsTable|\Cake\ORM\Association\HasMany $Reviews
 *
 * @method \App\Model\Entity\Thesis get($primaryKey, $options = [])
 * @method \App\Model\Entity\Thesis newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Thesis[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Thesis|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Thesis|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Thesis patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Thesis[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Thesis findOrCreate($search, callable $callback = null, $options = [])
 */
class ThesesTable extends Table
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

        $this->setTable('theses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ThesisTopics', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('Consultations', [
            'foreignKey' => 'thesis_id'
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'thesis_id'
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
            ->scalar('thesis_pdf')
            ->maxLength('thesis_pdf', 255)
            ->allowEmpty('thesis_pdf');

        $validator
            ->scalar('supplements')
            ->maxLength('supplements', 255)
            ->allowEmpty('supplements');

        $validator
            ->allowEmpty('internal_consultant_grade');

        $validator
            ->boolean('handed_in')
            ->allowEmpty('handed_in');

        $validator
            ->boolean('accepted')
            ->allowEmpty('accepted');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

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

        return $rules;
    }
}
