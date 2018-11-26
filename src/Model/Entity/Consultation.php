<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Consultation Entity
 *
 * @property int $id
 * @property bool|null $accepted
 * @property int|null $thesis_id
 *
 * @property \App\Model\Entity\Thesis $thesis
 * @property \App\Model\Entity\ConsultationOccasion[] $consultation_occasions
 */
class Consultation extends Entity
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
        'accepted' => true,
        'thesis_id' => true,
        'thesis' => true,
        'consultation_occasions' => true
    ];
}
