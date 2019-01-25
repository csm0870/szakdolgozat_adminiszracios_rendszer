<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConsultationOccasion Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $activity
 * @property int|null $consultation_id
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Consultation $consultation
 */
class ConsultationOccasion extends Entity
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
        'date' => true,
        'activity' => true,
        'consultation_id' => true,
        'created' => true,
        'consultation' => true
    ];
}
