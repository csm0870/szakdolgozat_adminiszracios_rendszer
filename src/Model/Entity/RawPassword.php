<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RawPassword Entity
 *
 * @property int $id
 * @property string|null $password
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class RawPassword extends Entity
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
        'password' => true,
        'user_id' => true,
        'user' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
