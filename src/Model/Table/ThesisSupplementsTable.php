<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisSupplements Model
 *
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\BelongsTo $ThesisTopics
 *
 * @method \App\Model\Entity\ThesisSupplement get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThesisSupplement newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThesisSupplement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThesisSupplement|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisSupplement|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisSupplement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisSupplement[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisSupplement findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThesisSupplementsTable extends Table
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

        $this->setTable('thesis_supplements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', ['file' =>['path' =>'files{DS}thesis_supplements{DS}']]);

        $this->belongsTo('ThesisTopics', [
            'foreignKey' => 'thesis_topic_id'
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
            ->allowEmpty('file');

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
    
    /**
     * Törlés utáni callback
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function afterDelete($event, $entity, $options){
        if(!empty($entity->file)){ //Fájl fizikai törlése, ha van
            unlink(ROOT . DS . 'files' . DS . 'thesis_supplements'. DS . $entity->file);
        }
    }
}
