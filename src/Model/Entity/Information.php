<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Information Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $filling_in_topic_form_begin_date
 * @property \Cake\I18n\FrozenDate|null $filling_in_topic_form_end_date
 * @property string|null $encryption_requlation
 */
class Information extends Entity
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
        'filling_in_topic_form_begin_date' => true,
        'filling_in_topic_form_end_date' => true,
        'encryption_requlation' => true
    ];
}
