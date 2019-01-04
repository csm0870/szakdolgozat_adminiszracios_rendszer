<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InternalConsultant Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $position
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $department_id
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ThesisTopic[] $thesis_topics
 */
class InternalConsultant extends Entity
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
        'name' => true,
        'position' => true,
        'created' => true,
        'modified' => true,
        'department_id' => true,
        'user_id' => true,
        'department' => true,
        'user' => true,
        'thesis_topics' => true
    ];
}
