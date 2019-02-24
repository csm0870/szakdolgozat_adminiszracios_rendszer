<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThesisTopics Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Years
 * @property |\Cake\ORM\Association\BelongsTo $Years
 * @property \App\Model\Table\InternalConsultantsTable|\Cake\ORM\Association\BelongsTo $InternalConsultants
 * @property \App\Model\Table\LanguagesTable|\Cake\ORM\Association\BelongsTo $Languages
 * @property \App\Model\Table\StudentsTable|\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\ThesisTopicStatusesTable|\Cake\ORM\Association\BelongsTo $ThesisTopicStatuses
 * @property \App\Model\Table\OfferedTopicsTable|\Cake\ORM\Association\BelongsTo $OfferedTopics
 * @property \App\Model\Table\ConsultationsTable|\Cake\ORM\Association\HasMany $Consultations
 * @property \App\Model\Table\ReviewsTable|\Cake\ORM\Association\HasMany $Reviews
 * @property \App\Model\Table\ThesisSupplementsTable|\Cake\ORM\Association\HasMany $ThesisSupplements
 *
 * @method \App\Model\Entity\ThesisTopic get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThesisTopic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThesisTopic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopic|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThesisTopic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThesisTopic findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThesisTopicsTable extends Table
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

        $this->setTable('thesis_topics');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StartingYears', [
            'foreignKey' => 'starting_year_id',
            'className' => 'Years'
        ]);
        $this->belongsTo('ExpectedEndingYears', [
            'foreignKey' => 'expected_ending_year_id',
            'className' => 'Years'
        ]);
        $this->belongsTo('InternalConsultants', [
            'foreignKey' => 'internal_consultant_id'
        ]);
        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id'
        ]);
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id'
        ]);
        $this->belongsTo('ThesisTopicStatuses', [
            'foreignKey' => 'thesis_topic_status_id'
        ]);
        $this->belongsTo('OfferedTopics', [
            'foreignKey' => 'offered_topic_id'
        ]);
        $this->hasMany('Consultations', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('Reviews', [
            'foreignKey' => 'thesis_topic_id'
        ]);
        $this->hasMany('ThesisSupplements', [
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->notEmpty('title', __('Cím megadása kötelező.'));

        $validator
            ->scalar('description')
            ->notEmpty('description', __('Leírás megadása kötelező.'));

        $validator
            ->boolean('is_thesis')
            ->notEmpty('is_thesis', __('Dolgozat típusának megadása kötelező.'));

        $validator
            ->boolean('encrypted')
            ->notEmpty('encrypted', __('Titkosítottság megadása kötelező.'));

        $validator
            ->boolean('starting_semester')
            ->notEmpty('starting_semester', __('Kezdési félév megadása kötelező.'));

        $validator
            ->boolean('expected_ending_semester')
            ->notEmpty('expected_ending_semester', __('Kezdési tanév megadása kötelező.'));

        $validator
            ->scalar('cause_of_no_external_consultant')
            ->allowEmpty('cause_of_no_external_consultant');

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
            ->email('external_consultant_email', __('Nem megfelelő e-mail cím formátum.'))
            ->maxLength('external_consultant_email', 60)
            ->allowEmpty('external_consultant_email');

        $validator
            ->scalar('external_consultant_phone_number')
            ->maxLength('external_consultant_phone_number', 60)
            ->allowEmpty('external_consultant_phone_number');

        $validator
            ->scalar('external_consultant_address')
            ->maxLength('external_consultant_address', 80)
            ->allowEmpty('external_consultant_address');

        $validator
            ->boolean('thesis_accepted')
            ->allowEmpty('thesis_accepted');

        $validator
            ->allowEmpty('internal_consultant_grade');

        $validator
            ->scalar('first_thesis_subject_failed_suggestion')
            ->allowEmpty('first_thesis_subject_failed_suggestion');

        $validator
            ->scalar('cause_of_rejecting_thesis_supplements')
            ->allowEmpty('cause_of_rejecting_thesis_supplements');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');
        
        $validator
            ->notEmpty('student_id', __('A hallgató megadása kötelező.'));

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
        $rules->add($rules->existsIn(['starting_year_id'], 'StartingYears'));
        $rules->add($rules->existsIn(['expected_ending_year_id'], 'ExpectedEndingYears'));
        $rules->add($rules->existsIn(['internal_consultant_id'], 'InternalConsultants'));
        $rules->add($rules->existsIn(['language_id'], 'Languages'));
        $rules->add($rules->existsIn(['student_id'], 'Students'));
        $rules->add($rules->existsIn(['thesis_topic_status_id'], 'ThesisTopicStatuses'));
        $rules->add($rules->existsIn(['offered_topic_id'], 'OfferedTopics'));

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
        if($entity->cause_of_no_external_consultant === null){ //Ha van külső konzulens
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
        }elseif(empty($entity->cause_of_no_external_consultant)){
            //Ha nincs külső konzulens, akkor annak indoklása kötelező
            $entity->setError('cause_of_no_external_consultant', __('Külső konzulenstől való eltekintés indoklása kötelező.'));
            return false;
        }
        
        return true;
    }
    
    /**
     * Mentés után callback
     * 
     * Itt majd az egyes állapotokból a másikba történő modosuláskor a különböző értékek resetelése kell, vagy akár emailek küldése iss
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function afterSave($event, $entity, $options){}
}
