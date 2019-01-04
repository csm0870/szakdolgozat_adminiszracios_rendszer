<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Thesis Entity
 *
 * @property int $id
 * @property string|null $thesis_pdf
 * @property string|null $supplements
 * @property int|null $internal_consultant_grade
 * @property bool|null $handed_in
 * @property bool|null $accepted
 * @property bool|null $deleted
 * @property int|null $thesis_topic_id
 *
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
 * @property \App\Model\Entity\Consultation[] $consultations
 * @property \App\Model\Entity\Review[] $reviews
 */
class Thesis extends Entity
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
        'thesis_pdf' => true,
        'supplements' => true,
        'internal_consultant_grade' => true,
        'handed_in' => true,
        'accepted' => true,
        'deleted' => true,
        'thesis_topic_id' => true,
        'thesis_topic' => true,
        'consultations' => true,
        'reviews' => true
    ];
}
