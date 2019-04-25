<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OfferedTopics Model
 *
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property |\Cake\ORM\Association\BelongsTo $Languages
 * @property \App\Model\Table\ThesisTopicsTable|\Cake\ORM\Association\HasMany $ThesisTopics
 *
 * @method \App\Model\Entity\OfferedTopic get($primaryKey, $options = [])
 * @method \App\Model\Entity\OfferedTopic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OfferedTopic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OfferedTopic|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OfferedTopic|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OfferedTopic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OfferedTopic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OfferedTopic findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OfferedTopicsTable extends Table
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

        $this->setTable('offered_topics');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('InternalConsultants', [
            'foreignKey' => 'internal_consultant_id'
        ]);
        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id'
        ]);
        $this->hasOne('ThesisTopics', [
            'foreignKey' => 'offered_topic_id'
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->notEmpty('title', __('Cím megadása kötelező.'))
            ->requirePresence('title', 'create', __('Cím megadása kötelező.'));

        $validator
            ->scalar('description')
            ->notEmpty('description', __('Leírás megadása kötelező.'))
            ->requirePresence('description', 'create', __('Leírás megadása kötelező.'));

        $validator
            ->boolean('confidential')
            ->notEmpty('confidential', __('Titkosság megadása kötelező.'))
            ->requirePresence('confidential', 'create', __('Titkosság megadása kötelező.'));

        $validator
            ->boolean('is_thesis')
            ->notEmpty('is_thesis', __('Téma típusának megadása kötelező.'))
            ->requirePresence('is_thesis', 'create', __('Téma típusának megadása kötelező.'));

        $validator
            ->boolean('has_external_consultant')
            ->notEmpty('has_external_consultant', __('Kötelező megadni, hogy van-e külső konzulens.'));

        $validator
            ->scalar('external_consultant_name')
            ->maxLength('external_consultant_name', 50)
            ->allowEmpty('external_consultant_name');

        $validator
            ->scalar('external_consultant_workplace')
            ->maxLength('external_consultant_workplace', 50)
            ->allowEmpty('external_consultant_workplace');

        $validator
            ->scalar('external_consultant_position')
            ->maxLength('external_consultant_position', 50)
            ->allowEmpty('external_consultant_position');

        $validator
            ->scalar('external_consultant_email')
            ->maxLength('external_consultant_email', 60)
            ->allowEmpty('external_consultant_email');

        $validator
            ->scalar('external_consultant_phone_number')
            ->maxLength('external_consultant_phone_number', 50)
            ->allowEmpty('external_consultant_phone_number');

        $validator
            ->scalar('external_consultant_address')
            ->maxLength('external_consultant_address', 80)
            ->allowEmpty('external_consultant_address');
        
        $validator
            ->notEmpty('language_id', __('Nyelv megadása kötelező.'))
            ->requirePresence('language_id', 'create', __('Nyelv megadása kötelező.'));

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
        $rules->add($rules->existsIn(['internal_consultant_id'], 'InternalConsultants'));
        $rules->add($rules->existsIn(['language_id'], 'Languages'));

        return $rules;
    }
    
    /**
     * Mentés előtti callback
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function beforeSave($event, $entity, $options){
        if(isset($entity->has_external_consultant) && $entity->has_external_consultant === true){ //Ha van külső konzulens
            //Külső konzulens adatainak ellenőrzése: nem lehetnek üresek
            
            $ok = true;
            if(empty($entity->external_consultant_name)){
                $entity->setError('external_consultant_name', __('Külső konzulens nevének megadása kötelező.'));
                $ok = false;
            }
            if(empty($entity->external_consultant_workplace)){
                $entity->setError('external_consultant_workplace', __('Külső konzulens munkahelyének megadása kötelező.'));
                $ok = false;
            }
            if(empty($entity->external_consultant_position)){
                $entity->setError('external_consultant_position', __('Külső konzulens poziciójának megadása kötelező.'));
                $ok = false;
            }
            if(empty($entity->external_consultant_email)){
                $entity->setError('external_consultant_email', __('Külső konzulens e-mail címének megadása kötelező.'));
                $ok = false;
            }
            if(empty($entity->external_consultant_phone_number)){
                $entity->setError('external_consultant_phone_number', __('Külső konzulens telefonszámának megadása kötelező.'));
                $ok = false;
            }
            if(empty($entity->external_consultant_address)){
                $entity->setError('external_consultant_address', __('Külső konzulens címének megadása kötelező.'));
                $ok = false;
            }
            
            return $ok;
        }
        
        //Ha nincs külső konzulens
        if(isset($entity->has_external_consultant) && $entity->has_external_consultant === false){
            //Külső konzulensi mezők resetelése
            $entity->external_consultant_name = null;
            $entity->external_consultant_workplace = null;
            $entity->external_consultant_position = null;
            $entity->external_consultant_email = null;
            $entity->external_consultant_phone_number = null;
            $entity->external_consultant_address = null;
        }
        
        return true;
    }
}
