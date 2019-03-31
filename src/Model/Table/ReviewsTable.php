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
        $this->addBehavior('Josegonzalez/Upload.Upload', ['confidentiality_contract' => ['path' =>'files{DS}confidentiality_contracts{DS}'],
                                                          'review_doc' => ['path' =>'files{DS}review_docs{DS}']]);

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
            ->allowEmpty('structure_and_style_point')
            ->range('structure_and_style_point', [0, 10], __('A pontszámnak 0 és 10 között kell lennie.'));

        $validator
            ->scalar('cause_of_structure_and_style_point')
            ->allowEmpty('cause_of_structure_and_style_point');

        $validator
            ->allowEmpty('processing_literature_point')
            ->range('processing_literature_point', [0, 10], __('A pontszámnak 0 és 10 között kell lennie.'));

        $validator
            ->scalar('cause_of_processing_literature_point')
            ->allowEmpty('cause_of_processing_literature_point');

        $validator
            ->allowEmpty('writing_up_the_topic_point')
            ->range('writing_up_the_topic_point', [0, 20], __('A pontszámnak 0 és 20 között kell lennie.'));

        $validator
            ->scalar('cause_of_writing_up_the_topic_point')
            ->allowEmpty('cause_of_writing_up_the_topic_point');

        $validator
            ->allowEmpty('practical_applicability_point')
            ->range('practical_applicability_point', [0, 10], __('A pontszámnak 0 és 10 között kell lennie.'));

        $validator
            ->scalar('cause_of_practical_applicability_point')
            ->allowEmpty('cause_of_practical_applicability_point');

        $validator
            ->scalar('general_comments')
            ->allowEmpty('general_comments');

        $validator
            ->scalar('cause_of_rejecting_confidentiality_contract')
            ->allowEmpty('cause_of_rejecting_confidentiality_contract');

        $validator
            ->allowEmpty('review_doc')
            ->add('review_doc', 'custom', [ //Csak PDF lehet a fájlformátum
                    'rule' => 'allowOnlyPdf',
                    'provider' => 'table',
                    'message' => __('Csak PDF a megengedett fájlformátum.')
                ]);
        
        $validator
            ->scalar('cause_of_rejecting_review')
            ->allowEmpty('cause_of_rejecting_review');
        
        $validator
            ->allowEmpty('confidentiality_contract')
            ->add('confidentiality_contract', 'custom', [ //Csak PDF lehet a fájlformátum
                    'rule' => 'allowOnlyPdf',
                    'provider' => 'table',
                    'message' => __('Csak PDF a megengedett fájlformátum.')
                ]);

        $validator
            ->allowEmpty('confidentiality_contract_status');

        $validator
            ->allowEmpty('review_status');

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
    
    /**
     * Mentés előtti callback
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function beforeSave($event, $entity, $options){
    }
    
    /**
     * Mentés után callback
     * 
     * @param type $event
     * @param type $entity
     * @param type $options
     */
    public function afterSave($event, $entity, $options){
        
        //======================================================================
        // ÉRTESÍTÉSEK KEZDETE
        //======================================================================
        
        //Feltöltött titoktartási szerződést véglegesítette a bíráló
        if($entity->getOriginal('confidentiality_contract_status') == 1 && $entity->confidentiality_contract_status == 2){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id],
                                                             'contain' => ['Students', 'InternalConsultants']])->first();
            
            if(!empty($thesisTopic) && $thesisTopic->has('student') && $thesisTopic->has('internal_consultant')){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->ThesisTopics->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);

                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'A bíráló feltöltötte a titoktartási szerződést. Ellenőrízze a megfelelőségét!';
                    $notification->message = 'A bírálathoz tartozó dolgozat adatai:' . '<br/>' .
                                             'Hallgató: ' . h($thesisTopic->student->name) . ' (' . h($thesisTopic->student->neptun) . ')<br/>' .
                                             'Téma címe: ' . h($thesisTopic->title) . '<br/>' .
                                             'Belső konzulens: ' . h($thesisTopic->internal_consultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A tanszékvezető elutasítja a bíráló feltöltött titoktartási szerződését
        if($entity->getOriginal('confidentiality_contract_status') == 2 && $entity->confidentiality_contract_status == 3){
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id]])->first();
            $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $entity->reviewer_id],
                                                       'contain' => ['Users']])->first();
            
            if(!empty($thesisTopic))
                $language = $this->ThesisTopics->Languages->find('all', ['conditions' => ['Languages.id' => $thesisTopic->language_id]])->first();
            
            if(!empty($thesisTopic) && !empty($reviewer) && $reviewer->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $reviewer->user->id;
                $notification->unread = true;
                $notification->subject = 'A tanszékvezető elutasította a feltöltött titoktartási szerződését.';
                $notification->message = 'Dolgozat címe: ' . h($thesisTopic->title) . '<br/>' .
                                         'Titkos: ' . ($thesisTopic->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                         (!empty($language) ? 'Nyelv: ' . h($language->name) . '<br/>' : '') .
                                         'Újra feltöltheti a titoktartási szerződést.' . '<br/>' .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'reviewer'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A tanszékvezető elfogadja a bíráló feltöltött titoktartási szerződését
        if($entity->getOriginal('confidentiality_contract_status') == 2 && $entity->confidentiality_contract_status == 4){
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id]])->first();
            $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $entity->reviewer_id],
                                                       'contain' => ['Users']])->first();
            
            if(!empty($thesisTopic))
                $language = $this->ThesisTopics->Languages->find('all', ['conditions' => ['Languages.id' => $thesisTopic->language_id]])->first();
            
            if(!empty($thesisTopic) && !empty($reviewer) && $reviewer->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $reviewer->user->id;
                $notification->unread = true;
                $notification->subject = 'A tanszékvezető elfogadta a feltöltött titoktartási szerződését. Bírálja a dolgozatot!';
                $notification->message = 'Dolgozat címe: ' . h($thesisTopic->title) . '<br/>' .
                                         'Titkos: ' . ($thesisTopic->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                         (!empty($language) ? 'Nyelv: ' . h($language->name) . '<br/>' : '') .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id, 'prefix' => 'reviewer'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A bíráló véglegesíti a feltöltött bírálati lapot (már az elektronikus bírálat is megvan)
        if($entity->getOriginal('review_status') == 3 && $entity->review_status == 4){
            $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
            
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id],
                                                             'contain' => ['Students', 'InternalConsultants']])->first();
            
            if(!empty($thesisTopic) && $thesisTopic->has('student') && $thesisTopic->has('internal_consultant')){
                //Tanszékvezetők, itt ha a belső konzulens egy tanszékhez tartozna, akkor annak a tanszékvezetője kapná csak az értesítést
                $head_of_departments = $this->ThesisTopics->Students->Users->find('all', ['conditions' => ['group_id' => 3]]);

                foreach($head_of_departments as $head_of_department){
                    $notification = $Notifications->newEntity();
                    $notification->user_id = $head_of_department->id;
                    $notification->unread = true;
                    $notification->subject = 'Egy bírálatra küldött dolgozat bírálva lett. Ellenőrízze a megfelelőségét!';
                    $notification->message = 'A bírálathoz tartozó dolgozat adatai:' . '<br/>' .
                                             'Hallgató: ' . h($thesisTopic->student->name) . ' (' . h($thesisTopic->student->neptun) . ')<br/>' .
                                             'Téma címe: ' . h($thesisTopic->title) . '<br/>' .
                                             'Belső konzulens: ' . h($thesisTopic->internal_consultant->name) . '<br/>' .
                                             '<a href="' . \Cake\Routing\Router::url(['controller' => 'Reviews', 'action' => 'check_review', $thesisTopic->id, 'prefix' => 'head_of_department'], true) . '">' . 'Részletek megtekintése' . '</a>';

                    $Notifications->save($notification);
                }
            }
        }
        
        //A tanszékvezető elutasítja a bírálatot
        if($entity->getOriginal('review_status') == 4 && $entity->review_status == 5){
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id]])->first();
            $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $entity->reviewer_id],
                                                       'contain' => ['Users']])->first();
            
            if(!empty($thesisTopic))
                $language = $this->ThesisTopics->Languages->find('all', ['conditions' => ['Languages.id' => $thesisTopic->language_id]])->first();
            
            if(!empty($thesisTopic) && !empty($reviewer) && $reviewer->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $reviewer->user->id;
                $notification->unread = true;
                $notification->subject = 'A tanszékvezető elutasított a bírálatot. Bírálja újra a dolgozatot!';
                $notification->message = 'Dolgozat címe: ' . h($thesisTopic->title) . '<br/>' .
                                         'Titkos: ' . ($thesisTopic->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                         (!empty($language) ? 'Nyelv: ' . h($language->name) . '<br/>' : '') .
                                         '<a href="' . \Cake\Routing\Router::url(['controller' => 'Reviews', 'action' => 'review', $thesisTopic->id, 'prefix' => 'reviewer'], true) . '">' . 'Részletek megtekintése' . '</a>';

                $Notifications->save($notification);
            }
        }
        
        //A tanszékvezető elfogadja a bírálatot
        if($entity->getOriginal('review_status') == 4 && $entity->review_status == 6){
            $thesisTopic = $this->ThesisTopics->find('all', ['conditions' => ['ThesisTopics.id' => $entity->thesis_topic_id]])->first();
            $reviewer = $this->Reviewers->find('all', ['conditions' => ['Reviewers.id' => $entity->reviewer_id],
                                                       'contain' => ['Users']])->first();
            
            if(!empty($thesisTopic))
                $language = $this->ThesisTopics->Languages->find('all', ['conditions' => ['Languages.id' => $thesisTopic->language_id]])->first();
            
            if(!empty($thesisTopic) && !empty($reviewer) && $reviewer->has('user')){
                $Notifications = \Cake\ORM\TableRegistry::get('Notifications');
                
                $notification = $Notifications->newEntity();
                $notification->user_id = $reviewer->user->id;
                $notification->unread = true;
                $notification->subject = 'A tanszékvezető elfogadta a bírálatot. További teendője nincs.';
                $notification->message = 'Dolgozat címe: ' . h($thesisTopic->title) . '<br/>' .
                                         'Titkos: ' . ($thesisTopic->confidential === true ? 'igen' : 'nem') . '<br/>' .
                                         (!empty($language) ? 'Nyelv: ' . h($language->name) : '');

                $Notifications->save($notification);
            }
            
            //======================================================================
            // ÉRTESÍTÉSEK VÉGE
            //======================================================================
        }
    }
    
    /**
     * Csak PDF a megengedett fájlformátum
     * 
     * @param type $value
     * @param array $context
     * @return boolean
     */
    public function allowOnlyPdf($value, array $context){
        if(!empty($value) && !empty($value['name'])){
            $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
            if($ext != 'pdf'){
                return false;
            }
        }

        return true;
    }
}
