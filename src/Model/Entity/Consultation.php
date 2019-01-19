<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Consultation Entity
 *
 * @property int $id
 * @property bool|null $accepted
 * @property int|null $thesis_topic_id
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
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
        'thesis_topic_id' => true,
        'created' => true,
        'thesis_topic' => true,
        'consultation_occasions' => true
    ];
}
