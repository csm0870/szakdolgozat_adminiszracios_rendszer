<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FailedTopicSuggestion Entity
 *
 * @property int $id
 * @property string|null $suggestion
 * @property bool|null $new_topic_by_external_consultant
 * @property bool|null $new_topic_by_head_of_department
 * @property int|null $thesis_topic_id
 *
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
 */
class FailedTopicSuggestion extends Entity
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
        'suggestion' => true,
        'new_topic_by_external_consultant' => true,
        'new_topic_by_head_of_department' => true,
        'thesis_topic_id' => true,
        'thesis_topic' => true
    ];
}
