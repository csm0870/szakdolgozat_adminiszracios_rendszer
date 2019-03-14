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
        $this->hasOne('Reviews', [
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
            ->boolean('confidential')
            ->notEmpty('confidential', __('Titkosítottság megadása kötelező.'));

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
            ->email('external_consultant_email', false, __('Nem megfelelő e-mail cím formátum.'))
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
            ->boolean('accepted_thesis_data_applyed_to_neptun')
            ->allowEmpty('accepted_thesis_data_applyed_to_neptun');

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
            ->notEmpty('internal_consultant_id', __('Belső konzulens megadása kötelező.'));
        
        $validator
            ->notEmpty('language_id', __('Nyelv megadása kötelező.'));
        
        $validator
            ->notEmpty('student_id', __('Hallgató megadása kötelező.'));
        
        $validator
            ->notEmpty('starting_year_id', __('Kezdési tanév megadása kötelező.'));
        
        $validator
            ->notEmpty('expected_ending_year_id', __('Várható leadási tanév megadása kötelező.'));

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
        $ok = true;
        
        //Annak a vizsgálata, hogy a kezdési tanév nem lehet nagyobb a várható leadási tanévnél
        if(!empty($entity->starting_year_id) && !empty($entity->expected_ending_year_id)){
            $starting_year = $this->StartingYears->find('all', ['conditions' => ['id' => $entity->starting_year_id]])->first();
            $ending_year = $this->StartingYears->find('all', ['conditions' => ['id' => $entity->expected_ending_year_id]])->first();
            
            if(!empty($starting_year) && !empty($ending_year)){
                if($starting_year->year > $ending_year->year){
                    $entity->setError('starting_year_id', __('A kezdési tanév nem lehet nagyobb, mint a várhat leadási tanév.'));
                    $ok = false;
                }
            }
        }
        
        if($entity->cause_of_no_external_consultant === null){ //Ha van külső konzulens
            //Külső konzulens adatainak ellenőrzése: nem lehetnek üresek
            
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
        }elseif(empty($entity->cause_of_no_external_consultant)){
            //Ha nincs külső konzulens, akkor annak indoklása kötelező
            $entity->setError('cause_of_no_external_consultant', __('Külső konzulenstől való eltekintés indoklása kötelező.'));
            $ok = false;
        }
        
        if(!empty($entity->internal_consultant_grade) && !in_array($entity->internal_consultant_grade, [1, 2, 3, 4, 5])){
            $entity->setError('internal_consultant_grade', __('A jegy csak 1, 2, 3, 4, 5 értéket vehet fel.'));
            $ok = false;
        }
        
        return $ok;
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
    public function afterSave($event, $entity, $options){
        //Ha belső konzulens értékelte a dolgozatot, és a bíráló is már bírálta, akkor megvizsgáljuk a két értékelést,
        //és azoknak megfelelően elfogadott lesz a dolgozat vagy újra második diplomakurzust kell felvennie a hallgatónak
        if($entity->thesis_topic_status_id == 24 && $entity->internal_consultant_grade !== null){
            $thesisTopic = $this->find('all', ['conditions' => ['ThesisTopics.id' => $entity->id],
                                               'contain' => ['Reviews', 'Consultations']])->first();
            
            
            if($thesisTopic->has('review')){
                $total_points = 0;
                $grade = 1;
                
                //Összpontszám kiszámítása
                $total_points = (empty($thesisTopic->review->structure_and_style_point) ? 0 : $thesisTopic->review->structure_and_style_point) +
                            (empty($thesisTopic->review->processing_literature_point) ? 0 : $thesisTopic->review->processing_literature_point) +
                            (empty($thesisTopic->review->writing_up_the_topic_point) ? 0 : $thesisTopic->review->writing_up_the_topic_point) +
                            (empty($thesisTopic->review->practical_applicability_point) ? 0 : $thesisTopic->review->practical_applicability_point);
        
                //Jegy kiszámítása
                if(!empty($thesisTopic->review->structure_and_style_point) && !empty($thesisTopic->review->processing_literature_point) &&
                   !empty($thesisTopic->review->writing_up_the_topic_point) && !empty($thesisTopic->review->practical_applicability_point)){

                    if($total_points >= 45) $grade = 5;
                    else if($total_points < 45 && $total_points >= 38) $grade = 4;
                    else if($total_points < 38 && $total_points >= 31) $grade = 3;
                    else if($total_points < 31 && $total_points >= 26) $grade = 2;
                }
                
                if($grade > 1 && $thesisTopic->internal_consultant_grade > 1){
                    $thesisTopic->thesis_topic_status_id = 25;
                    $thesisTopic->accepted_thesis_data_applyed_to_neptun = false; //A Neptun rendszerbe még nem lettek felvive a megfelelő adatok az elfogadott dolgozatról
                }else $thesisTopic->thesis_topic_status_id = 15;
                
                $this->save($thesisTopic);
            }
        }
    }
}
