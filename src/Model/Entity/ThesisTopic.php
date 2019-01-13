<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ThesisTopic Entity
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $cause_of_no_external_consultant
 * @property bool|null $accepted_by_internal_consultant
 * @property bool|null $accepted_by_head_of_department
 * @property bool|null $accepted_by_external_consultant
 * @property bool|null $modifiable
 * @property bool|null $deleted
 * @property bool|null $is_thesis
 * @property bool|null $encrypted
 * @property int|null $starting_year_id
 * @property bool|null $starting_semester
 * @property int|null $expected_ending_year_id
 * @property bool|null $expected_ending_semester
 * @property string|null $external_consultant_name
 * @property string|null $external_consultant_workplace
 * @property string|null $external_consultant_position
 * @property string|null $external_consultant_email
 * @property string|null $external_consultant_phone_number
 * @property string|null $external_consultant_address
 * @property int|null $internal_consultant_id
 * @property int|null $language_id
 * @property int|null $student_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\StartingYear $starting_year
 * @property \App\Model\Entity\AcceptedEndingYear $accepted_ending_year
 * @property \App\Model\Entity\InternalConsultant $internal_consultant
 * @property \App\Model\Entity\Language $language
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\FailedTopicSuggestion[] $failed_topic_suggestions
 * @property \App\Model\Entity\Thesis[] $theses
 */
class ThesisTopic extends Entity
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
        'cause_of_no_external_consultant' => true,
        'accepted_by_internal_consultant' => true,
        'accepted_by_head_of_department' => true,
        'accepted_by_external_consultant' => true,
        'modifiable' => true,
        'deleted' => true,
        'is_thesis' => true,
        'encrypted' => true,
        'starting_year_id' => true,
        'starting_semester' => true,
        'expected_ending_year_id' => true,
        'expected_ending_semester' => true,
        'external_consultant_name' => true,
        'external_consultant_workplace' => true,
        'external_consultant_position' => true,
        'external_consultant_email' => true,
        'external_consultant_phone_number' => true,
        'external_consultant_address' => true,
        'internal_consultant_id' => true,
        'language_id' => true,
        'student_id' => true,
        'created' => true,
        'modified' => true,
        'starting_year' => true,
        'accepted_ending_year' => true,
        'internal_consultant' => true,
        'language' => true,
        'student' => true,
        'failed_topic_suggestions' => true,
        'theses' => true
    ];
}
