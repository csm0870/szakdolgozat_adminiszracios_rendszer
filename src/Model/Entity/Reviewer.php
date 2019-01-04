<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Reviewer Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $workplace
 * @property string|null $position
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Review[] $reviews
 */
class Reviewer extends Entity
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
        'email' => true,
        'workplace' => true,
        'position' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'user' => true,
        'reviews' => true
    ];
}
