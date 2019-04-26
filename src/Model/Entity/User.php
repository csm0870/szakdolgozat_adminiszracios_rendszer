<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $password
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $group_id
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\InternalConsultant[] $internal_consultants
 * @property \App\Model\Entity\Notification[] $notifications
 * @property \App\Model\Entity\RawPassword[] $raw_passwords
 * @property \App\Model\Entity\Reviewer[] $reviewers
 * @property \App\Model\Entity\Student[] $students
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'email' => true,
        'password' => true,
        'created' => true,
        'modified' => true,
        'group_id' => true,
        'group' => true,
        'internal_consultant' => true,
        'notifications' => true,
        'raw_password' => true,
        'reviewer' => true,
        'student' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    
    protected function _setPassword($password){
        if(strlen($password) > 0){
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
    
    public function parentNode(){
        if(!$this->id){
            return null;
        }
        if(isset($this->group_id)){
            $groupId = $this->group_id;
        }else{
            $Users = \Cake\ORM\TableRegistry::get('Users');
            $user = $Users->find('all', ['fields' => ['group_id']])->where(['id' => $this->id])->first();
            $groupId = $user->group_id;
        }
        if(!$groupId){
            return null;
        }
        return ['Groups' => ['id' => $groupId]];
    }
}
