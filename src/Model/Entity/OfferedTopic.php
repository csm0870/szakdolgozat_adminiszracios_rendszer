<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OfferedTopic Entity
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property bool|null $has_external_consultant
 * @property string|null $external_consultant_name
 * @property string|null $external_consultant_workplace
 * @property string|null $external_consultant_position
 * @property string|null $external_consultant_email
 * @property string|null $external_consultant_phone_number
 * @property string|null $external_consultant_address
 * @property int|null $internal_consultant_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\InternalConsultant $internal_consultant
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
 */
class OfferedTopic extends Entity
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
        'title' => true,
        'description' => true,
        'has_external_consultant' => true,
        'external_consultant_name' => true,
        'external_consultant_workplace' => true,
        'external_consultant_position' => true,
        'external_consultant_email' => true,
        'external_consultant_phone_number' => true,
        'external_consultant_address' => true,
        'internal_consultant_id' => true,
        'created' => true,
        'modified' => true,
        'internal_consultant' => true,
        'thesis_topic' => true
    ];
}
