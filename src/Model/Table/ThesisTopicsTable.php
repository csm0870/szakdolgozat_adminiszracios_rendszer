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
            ->notEmpty('title', __('Cím megadása kötelező.'))
            ->requirePresence('title', 'create', __('Cím megadása kötelező.'));

        $validator
            ->scalar('description')
            ->notEmpty('description', __('Leírás megadása kötelező.'))
            ->requirePresence('description', 'create', __('Leírás megadása kötelező.'));

        $validator
            ->boolean('is_thesis')
            ->notEmpty('is_thesis', __('Téma típusának megadása kötelező.'))
            ->requirePresence('is_thesis', 'create', __('Téma típusának megadása kötelező.'));

        $validator
            ->boolean('confidential')
            ->notEmpty('confidential', __('Titkosítottság megadása kötelező.'))
            ->requirePresence('confidential', 'create', __('Titkosítottság megadása kötelező.'));

        $validator
            ->boolean('starting_semester')
            ->notEmpty('starting_semester', __('Kezdési félév megadása kötelező.'))
            ->requirePresence('starting_semester', 'create', __('Kezdési félév megadása kötelező.'));

        $validator
            ->boolean('expected_ending_semester')
            ->notEmpty('expected_ending_semester', __('Kezdési tanév megadása kötelező.'))
            ->requirePresence('expected_ending_semester', 'create', __('Kezdési tanév megadása kötelező.'));

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
            ->date('handed_in_date')
            ->allowEmpty('handed_in_date');

        $validator
            ->scalar('proposal_for_amendment')
            ->allowEmpty('proposal_for_amendment');
        
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
            ->notEmpty('internal_consultant_id', __('Belső konzulens megadása kötelező.'))
            ->requirePresence('internal_consultant_id', 'create', __('Belső konzulens megadása kötelező.'));
        
        $validator
            ->notEmpty('language_id', __('Nyelv megadása kötelező.'))
            ->requirePresence('language_id', 'create', __('Nyelv megadása kötelező.'));
        
        $validator
            ->notEmpty('student_id', __('Hallgató megadása kötelező.'))
            ->requirePresence('student_id', 'create', __('Hallgató megadása kötelező..'));
        
        $validator
            ->notEmpty('starting_year_id', __('Kezdési tanév megadása kötelező.'))
            ->requirePresence('starting_year_id', 'create', __('Kezdési tanév megadása kötelező.'));
        
        $validator
            ->notEmpty('expected_ending_year_id', __('Várható leadási tanév megadása kötelező.'))
            ->requirePresence('expected_ending_year_id', 'create', __('Várható leadási tanév megadása kötelező.'));

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
        
        if(!empty($entity->internal_consultant_grade) && !in_array($entity->internal_consultant_grade, [2, 3, 4, 5])){
            $entity->setError('internal_consultant_grade', __('A jegy csak 2, 3, 4, 5 értéket vehet fel.'));
            $ok = false;
        }
        
        //======================================================================
        // ÁLLAPOTVÁLTOZÁSOKKOR AZ EGYES ADATOK RESETELÉSE (eleje)
        //======================================================================
        
        //A hallgató véglegesíti a témát
        if(($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize') ||
            $entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking') ||
            $entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')) &&
            $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic')){
            $entity->handed_in_date = date('Y-m-d');
        }
        
        //A téma elfogadott állapotba kerül
        if(($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') ||
            $entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic')) &&
            $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){
            $entity->proposal_for_amendment = null;
        }
        
        //Első diplomakurzus sikertelen, és a tanszékvezető engedi a téma folytatását
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){
            $entity->first_thesis_subject_failed_suggestion = null;
        }
        
        //A dolgozat a formai követelményeknek megfelelt
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable')){
            $entity->accepted_thesis_data_applyed_to_neptun = false;
            $entity->internal_consultant_grade = null;
        }
        
        //A szakdolgozatkezelő elfogadja a dolgozat mellékleteit
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')){
            $entity->cause_of_rejecting_thesis_supplements = null;
        }
        
        //Ha belső konzulens értékelte a dolgozatot, és a bíráló is már bírálta, akkor megvizsgáljuk a két értékelést,
        //és azoknak megfelelően elfogadott lesz a dolgozat vagy újra második diplomakurzust kell felvennie a hallgatónak
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed') &&
           $entity->internal_consultant_grade !== null ||
           $entity->getOriginal('internal_consultant_grade') === null && $entity->internal_consultant_grade !== null &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed')){
            $thesisTopic = $this->find('all', ['conditions' => ['ThesisTopics.id' => $entity->id],
                                               'contain' => ['Reviews']])->first();
            
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
                    elseif($total_points < 45 && $total_points >= 38) $grade = 4;
                    elseif($total_points < 38 && $total_points >= 31) $grade = 3;
                    elseif($total_points < 31 && $total_points >= 26) $grade = 2;
                }
                
                //Ha a bírálat legalább elégséges, akkor a dolgozat elfogadva, ha nem akkor újra mellékletek feltöltése lesz
                if($grade > 1) $entity->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted');
                else $entity->thesis_topic_status_id = \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable');
            }
        }
        
        //======================================================================
        // ÁLLAPOTVÁLTOZÁSOKKOR AZ EGYES ADATOK RESETELÉSE (vége)
        //======================================================================
        
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
        //======================================================================
        // ÉRTESÍTÉSEK KEZDETE
        //======================================================================
        
        //Kiírt téma lefoglalása
        if($entity->isNew() && $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            $student = $this->Students->find('all', ['conditions' => ['id' => $entity->student_id]])->first();
            $offered_topic = $this->OfferedTopics->find('all', ['conditions' => ['id' => $entity->offered_topic_id]])->first();
            
            if(!empty($internalConsultant) && $internalConsultant->has('user') && !empty($student) && !empty($offered_topic)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy kiírt témára jelentkezett egy hallgató. Döntsön a foglalásról!';
                $notification->message = 'Jelentkezett hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'OfferedTopics', 'action' => 'details', $offered_topic->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //Kiírt témára való jelentkezés elutasítása
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingRejectedByInternalConsultant')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A téma foglalását a belső konzulens visszautasította.';
                $notification->message = 'A ' . h($entity->title) . ' című téma foglalását a ' . h($internalConsultant->name) . ' nevű belső konzulens visszautasította.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //Kiírt témára való jelentkezés elfogadása
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy kiírt téma foglalását a belső konzulens elfogadta.';
                $notification->message = 'A ' . h($entity->title) . ' című téma foglalását a ' . h($internalConsultant->name) . ' nevű belső konzulens elfogadta.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //Kiírt téma lefoglalásának visszavonása
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicBookingCanceledByStudent')){
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            $student = $this->Students->find('all', ['conditions' => ['id' => $entity->student_id]])->first();
            $offered_topic = $this->OfferedTopics->find('all', ['conditions' => ['id' => $entity->getOriginal('offered_topic_id')]])->first();
            
            if(!empty($internalConsultant) && $internalConsultant->has('user') && !empty($student) && !empty($offered_topic)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy témafoglalást a jelentkezett hallgató visszavonta.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title);

                $Notifications->save($notification);
            }
        }
        
        //Kiírt téma lefoglalásának hallgatói véglegesítése
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic')){
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            $student = $this->Students->find('all', ['conditions' => ['id' => $entity->student_id]])->first();
            $offered_topic = $this->OfferedTopics->find('all', ['conditions' => ['id' => $entity->offered_topic_id]])->first();
            
            if(!empty($internalConsultant) && $internalConsultant->has('user') && !empty($student) && !empty($offered_topic)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy témafoglalást a jelentkezett hallgató véglegesített. Döntsön az elfogadásáról!';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A hallgató véglegesítésíti a témát
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalize') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic')){
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            $student = $this->Students->find('all', ['conditions' => ['id' => $entity->student_id]])->first();
            
            if(!empty($internalConsultant) && $internalConsultant->has('user') && !empty($student)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy hallgató témát adott le Önhöz. Döntsön az elfogadásáról!';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens elutasítja a témát
        if($entity->getOriginal('thesis_topic_status_id') ==  \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id ==  \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByInternalConsultant')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témáját visszautasította a belső konzulens.';
                $notification->message = 'A ' . h($entity->title) . ' című témát a ' . h($internalConsultant->name) . ' nevű belső konzulens visszautasította.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';;
                
                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens elfogadja a témát
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témáját elfogadta a belső konzulens.';
                $notification->message = 'A ' . h($entity->title) . ' című témát a ' . h($internalConsultant->name) . ' nevű belső konzulens elfogadta. A téma tanszékvezető elfogadására vár.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            if(!empty($student) && !empty($internalConsultant)){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);
                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy téma a belső konzulens által el lett fogadva. Döntsön a téma elfogadásáról!';
                    $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                             'Téma címe: ' . h($entity->title) . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             ($entity->proposal_for_amendment === null ? '' : 'Ehhez a témához módosítási javaslatot adott. A téma a módosítás utáni állapotban van.' . '<br/>') .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A tanszékvezető elutasítja a témát
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartment')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témáját visszautasította a tanszékvezető.';
                $notification->message = 'A ' . h($entity->title) . ' című témát a tanszékvezető visszautasította.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';;
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott témát a tanszékvezető elutasított.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A tanszékvezető elfogadja a témát, és a külső konzulensi aláírás ellenőrzésére vár
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témáját elfogadta a tanszékvezető.';
                $notification->message = 'A ' . h($entity->title) . ' című témát a tanszékvezető elfogadta. A téma a külső konzulensi aláírás ellenőrzésére vár.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott témát a tanszékvezető elfogadott.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
            
            if(!empty($student) && !empty($internalConsultant)){
                //Témakezelők
                $topic_managers = $this->Students->Users->find('all', ['conditions' => ['group_id' => 4]]);
                foreach($topic_managers as $topic_manager){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $topic_manager->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy téma a tanszékvezető által el lett fogadva. Döntsön a külső konzulensi aláírás helyességéről.';
                    $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                             'Téma címe: ' . h($entity->title) . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'topic_manager'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        
        //A tanszékvezető javaslatot ad a téma módosítására
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A tanszékvezető módosítási javaslatot adott a leadott témájához.';
                $notification->message = 'A ' . h($entity->title) . ' című témához a tanszékvezető módosítási javaslatot adott.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';;
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott témához a tanszékvezető módosítási javaslatot adott.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A hallgató véglegesítésíti a témát, amelyhez a tanszékvezető javaslatot adott
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment') &&
            $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopic')){
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            $student = $this->Students->find('all', ['conditions' => ['id' => $entity->student_id]])->first();
            
            if(!empty($internalConsultant) && $internalConsultant->has('user') && !empty($student)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató módosított a témáján, amelynek a tanszékvezető a módosítását javasolta. Döntsön az elfogadásáról!';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A tanszékvezető elfogadja a témát, és a téma elfogadottá válik
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForHeadOfDepartmentAcceptingOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témáját elfogadta a tanszékvezető. A téma el van fogadva.';
                $notification->message = 'A ' . h($entity->title) . ' című témát a tanszékvezető elfogadta. A téma el van fogadva.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott témát elfogadott a tanszékvezető. A téma el van fogadva.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         'A téma el van fogadva.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A témakezelő elutasítja a témát
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByExternalConsultant')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témája el lett utasítva.';
                $notification->message = 'A ' . h($entity->title) . ' című el lett utasítva, a külső konzulensi aláírás nem felelt meg.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott téma el lett utasítva, a külső konzulensi aláírás nem felelt meg.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A témakezelő elfogadja a témát
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingExternalConsultantSignatureOfThesisTopic') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott témája el lett fogadva.';
                $notification->message = 'A ' . h($entity->title) . ' című el lett fogadva.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgató által leadott téma el lett fogadva.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens "első diplomakurzus sikertelen"-t rögzít
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'Az első diplomakurzus sikertelen. A folytatás a tanszékvezető döntésére vár.';
                $notification->message = 'A ' . h($entity->title) . ' című téma első diplomakurzusa sikertelen. A folytatásról a tanszékvezető dönt (új téma választása, vagy pedig a meglevő javítása).' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
                
            if(!empty($student) && !empty($internalConsultant)){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);

                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy hallgató nem teljesítette az első diplomakurzust. Döntsön a folytatásról!';
                    $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                             'Téma címe: ' . h($entity->title) . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             'Döntsön a folytattásról: a hallgató új témát válasszon, vagy pedig a meglevőt javítsa.' . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A tanszékvezető elutasítja a téma folytatását sikertelen első diplomakurzus esetén
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'Sikertelen első diplomakurzusról döntött a tanszékvezető.';
                $notification->message = 'A ' . h($entity->title) . ' című téma folytatását elutasította a tanszékvezető. Új témát kell választania.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgatónál sikertelen első diplomakurzusról döntést hozott a tanszékvezető.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         'A tanszékvezető elutasította a folytatást. A hallgatónak másik témát kell választania.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A tanszékvezető elfogadja a téma folytatását
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'Sikertelen első diplomakurzusról döntött a tanszékvezető.';
                $notification->message = 'A ' . h($entity->title) . ' című téma folytatását elfogadta. Újra felveheti az első diplomakurzust a meglevő témával.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
                
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó hallgatónál sikertelen első diplomakurzusról döntést hozott a tanszékvezető.';
                $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')<br/>' .
                                         'Téma címe: ' . h($entity->title) . '<br/>' .
                                         'A tanszékvezető elfogadta a téma folytatását, a hallgató ismét felveheti az első diplomakurzust a meglevő témával.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens rögzíti az "első diplomakurzus teljesítését"
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisTopicAccepted') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A belső konzulense rögzítette a témájához tartozó első diplomakurzus teljesítését.';
                $notification->message = 'A ' . h($entity->title) . ' című téma első diplomakurzusának teljesítését rögzítette a ' . h($internalConsultant->name) . ' nevű belső konzulense.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens rögzíti az "első diplomakurzus teljesítését"
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.FirstThesisSubjectSucceeded') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && $student->has('user') && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A belső konzulense rögzítette, hogy a ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a formai követelményeknek megfelelt.';
                $notification->message = 'A ' . h($entity->title) . ' című témához tartozó  ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a formai követelményeknek megfelelt, így feltölthetőek a mellékletek a bírálathoz.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            if(!empty($student) && !empty($internalConsultant)){
                //Szakdolgozatkezelők
                $thesis_managers = $this->Students->Users->find('all', ['conditions' => ['group_id' => 5]]);
                foreach($thesis_managers as $thesis_manager){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $thesis_manager->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a formai követelményeknek megfelelt.';
                    $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                             'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             'A téma címe felvihető a Neptun rendszerbe, az adatok felviteléről a bírálat után is fog kapni értesítést.' . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'thesis_manager'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A hallgató véglegesíti a mellékletek feltöltését
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizeOfUploadOfThesisSupplement') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id]])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            if(!empty($student) && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');

                //Szakdolgozatkezelők
                $thesis_managers = $this->Students->Users->find('all', ['conditions' => ['group_id' => 5]]);
                foreach($thesis_managers as $thesis_manager){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $thesis_manager->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy ' . ($entity->is_thesis === true ? 'szakdolgozathoz' : 'diplomamunkához') . ' mellékleteket töltött fel a hallgató. Ellenőrizze a mellékletek megfelelőségét!';
                    $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                             'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'thesis_manager'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A szakdolgozatkezelő elutasítja a dolgozat mellékleteket
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementsRejected')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' mellékletei vissza lettek utasítva.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' mellékletei vissza lettek utasítva. A mellékletek ismét feltölthetőek, illetve módosíthatóak.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A szakdolgozatkezelő elfogadja a dolgozat mellékleteket
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForCheckingOfThesisSupplements') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' mellékletei el lettek fogadva.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' mellékletei el lettek fogadva. A dolgozata már bírálatra küldhető.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' mellékleteit elfogadták. Jelölje ki a bírálót!';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         'A bíráló kijelölhető a dolgozathoz.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens értékelte a dolgozatot
        if($entity->getOriginal('internal_consultant_grade') === null && $entity->internal_consultant_grade !== null){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            
            if(!empty($student) && $student->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A belső konzulens értékelte a ' . ($entity->is_thesis === true ? 'szakdolgozatát' : 'diplomamunkáját') . '.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' értékelését a belső konzulens rögzítette' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A belső konzulens kijelölte a bírálót
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByInternalConsultant') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id]])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id]])->first();
            
            if(!empty($student) && !empty($internalConsultant)){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);

                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy belső konzulens javaslatot tett egy ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálójáról. Döntsön a bíráló személyéről!';
                    $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                             'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A tanszékvezető kijelöli a bírálót
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForDesignationOfReviewerByHeadOfDepartment') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview')){
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id]])->first();
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            
            
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálóját kijelölte a tanszékvezető.';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A dolgozatot a tanszékvezető bírálatra küldi
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.WatingForSendingToReview') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview')){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott ' . ($entity->is_thesis === true ? 'szakdolgozata' : 'diplomamunkája') . ' bírálatra lett küldve.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálatra lett küldve. A bírálat után megtekintheti a bírálatot.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálatra lett küldve.';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         'A bírálat után a bírálatot megtekintheti.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $Reviews = \Cake\ORM\TableRegistry::get('Reviews');
            $review = $Reviews->find('all', ['conditions' => ['Reviews.thesis_topic_id' => $entity->id],
                                             'contain' => ['Reviewers' => ['Users']]])->first();
            
            $language = $this->Languages->find('all', ['conditions' => ['Languages.id' => $entity->language_id]])->first();
            
            if(!empty($review) && $review->has('reviewer') && $review->reviewer->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $review->reviewer->user->id;
                $notification->unread = true;
                $notification->subject = 'Egy ' . ($entity->is_thesis === true ? 'szakdolgozathoz' : 'diplomamunkáhpz') . ' Önt jelölték ki bírálónak.';
                $notification->message = 'Dolgozat címe: ' . h($entity->title) . '<br/>' .
                                         'Titkos: ' . ($entity->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                         (!empty($language) ? 'Nyelv: ' . h($language->name) . '<br/>' : '') .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'reviewer'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A bírálat megszületik
        if($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed')){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A bírálatra küldött ' . ($entity->is_thesis === true ? 'szakdolgozata' : 'diplomamunkája') . ' bírálva lett. A bírálatot megtekintheti.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálva lett.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'Reviews', 'action' => 'checkReview', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' bírálva lett. A bírálatot megtekintheti.';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'Reviews', 'action' => 'checkReview', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
        }
        
        //A dolgozat értékelése alapján a dolgozat nem felelt meg (belső konzulens és a bíráló)
        if(in_array($entity->getOriginal('thesis_topic_status_id'), [\Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview'),
                                                                     \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed')]) &&
           $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisSupplementUploadable')){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott ' . ($entity->is_thesis === true ? 'szakdolgozata' : 'diplomamunkája') . ' értékelve lett. A dolgozat nem lett elfogadva.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' a bíráló értékelése alapján nem lett elfogadva.' . '<br/>' .
                                         'A dolgozat újra bírálásához javítson a dolgozaton, majd ismét feltöltheti a mellékleteket az újboli bírálathoz.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' értékelve lett. A dolgozat nem lett elfogadva.';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         'A bírálat alapján a dolgozat nem lett elfogadva. A hallgató a dolgozat módosítása után újra feltöltheti a mellékleteket, majd ismét bírálatra lehet bocsátani.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            if(!empty($student) && !empty($internalConsultant)){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);
                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy leadott ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' értékelve lett. A dolgozat nem lett elfogadva.';
                    $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                             'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             'A bírálat alapján a dolgozat nem lett elfogadva. A hallgató a dolgozat módosítása után újra feltöltheti a mellékleteket, majd ismét bírálatra lehet bocsátani.' . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A dolgozat el lett fogadva (belső konzulens és a bíráló értékelése is megfelelő volt)
        if(($entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed') &&
            $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')) ||
            $entity->getOriginal('thesis_topic_status_id') == \Cake\Core\Configure::read('ThesisTopicStatuses.Reviewed') &&
            $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Users']])->first();
            if(!empty($student) && $student->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $student->user_id;
                $notification->unread = true;
                $notification->subject = 'A leadott ' . ($entity->is_thesis === true ? 'szakdolgozata' : 'diplomamunkája') . ' el lett fogadva.';
                $notification->message = 'A ' . h($entity->title) . ' című ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' el lett fogadva. További teendője nincs a dolgozattal kapcsolatban.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'student'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            $internalConsultant = $this->InternalConsultants->find('all', ['conditions' => ['InternalConsultants.id' => $entity->internal_consultant_id],
                                                                           'contain' => ['Users']])->first();
            if(!empty($internalConsultant) && $internalConsultant->has('user')){
                $notification = $Notifications->newEntity();
                $notification->user_id = $internalConsultant->user_id;
                $notification->unread = true;
                $notification->subject = 'Egy Önhöz tartozó ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' el lett fogadva.';
                $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                         'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'internal_consultant'], true) . '">' . 'Részletek megtekintése' . '</a>';
            
                $Notifications->save($notification);
            }
            
            if(!empty($student) && !empty($internalConsultant)){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);
                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy leadott ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' el lett fogadva.';
                    $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                             'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                             'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                    
                    //Szakdolgozatkezelők
                    $thesis_managers = $this->Students->Users->find('all', ['conditions' => ['group_id' => 5]]);
                    foreach($thesis_managers as $thesis_manager){
                        $notification = $Notifications->newEntity();
                        $notification->user_id = $thesis_manager->id;
                        $notification->unread = true;
                        $notification->subject = 'Egy leadott ' . ($entity->is_thesis === true ? 'szakdolgozat' : 'diplomamunka') . ' el lett fogadva. Vigye fel az adatokat a Neptun rendszerbe!';
                        $notification->message = 'A téma címe: ' . h($entity->title) . '<br/>' .
                                                 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                                 'Belső konzulens: ' . h($internalConsultant->name) . '<br/>' .
                                                 '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $entity->id, 'prefix' => 'thesis_manager'], true) . '">' . 'Részletek megtekintése' . '</a>';

                        $Notifications->save($notification);
                    }
                }
            }
        }
        
        //A szakdolgozatkezelő rögzíti, hogy felvitte a dolgozat adatait a Neptun rendszerbe
        if(($entity->getOriginal('accepted_thesis_data_applyed_to_neptun') !== true &&
            $entity->accepted_thesis_data_applyed_to_neptun === true) && $entity->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.ThesisAccepted')){
            
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            $student = $this->Students->find('all', ['conditions' => ['Students.id' => $entity->student_id],
                                                     'contain' => ['Courses', 'CourseTypes', 'CourseLevels']])->first();
            if(!empty($student)){
                
                $can_go_to_final_exam = true;
                
                //Ha mérnökinformatikus és nincsenek ZV tárgyai, akkor nem mehet még vizsgára
                if($student->course_id == 1 && $student->final_exam_subjects_status != 3){
                    $can_go_to_final_exam = false;
                }
                
                if($can_go_to_final_exam === true){
                    //Halgatónál beállítjuk, hogy még nem ment át a ZV-n
                    $student->passed_final_exam = false;
                    $this->Students->save($student);
                    
                    //Záróvizsga összeállítók
                    $final_exam_organizers = $this->Students->Users->find('all', ['conditions' => ['group_id' => 8]]);
                    foreach($final_exam_organizers as $final_exam_organizer){
                        $notification = $Notifications->newEntity();
                        $notification->user_id = $final_exam_organizer->id;
                        $notification->unread = true;
                        $notification->subject = 'Egy hallgató megfelelt a záróvizsga követelményeknek. Záróvizsgára mehet.';
                        $notification->message = 'Hallgató: ' . h($student->name) . ' (' . h($student->neptun) . ')' . '<br/>' .
                                                 ($student->has('course') ? 'Szak: ' . h($student->course->name) . '<br/>' : '') .
                                                 ($student->has('course_type') ? 'Tagozat: ' . h($student->course_type->name) . '<br/>' : '') .
                                                 ($student->has('course_level') ? 'Képzési szint: ' . h($student->course_level->name) . '<br/>' : '') .
                                                 'Ez az üzenet nem jelenti azt, hogy a hallgató jelentkezett már záróvizsgára.' . '<br/>';
                        $Notifications->save($notification);
                    }
                }
            }
        }
        
        //======================================================================
        // ÉRTESÍTÉSEK VÉGE
        //======================================================================
    }
}
