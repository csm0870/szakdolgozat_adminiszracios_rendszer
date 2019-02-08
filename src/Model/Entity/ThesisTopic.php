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
 * @property string|null $first_thesis_subject_failed_suggestion
 * @property int|null $internal_consultant_grade
 * @property bool|null $thesis_accepted
 * @property int|null $student_id
 * @property int|null $thesis_topic_status_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Year $starting_year
 * @property \App\Model\Entity\Year $expected_ending_year
 * @property \App\Model\Entity\InternalConsultant $internal_consultant
 * @property \App\Model\Entity\Language $language
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\ThesisTopicStatus $thesis_topic_status
 * @property \App\Model\Entity\Consultation[] $consultations
 * @property \App\Model\Entity\Review[] $reviews
 * @property \App\Model\Entity\ThesisSupplement[] $thesis_supplements
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
        'first_thesis_subject_failed_suggestion' => true,
        'internal_consultant_grade' => true,
        'thesis_accepted' => true,
        'student_id' => true,
        'thesis_topic_status_id' => true,
        'created' => true,
        'modified' => true,
        'starting_year' => true,
        'expected_ending_year' => true,
        'internal_consultant' => true,
        'language' => true,
        'student' => true,
        'thesis_topic_status' => true,
        'consultations' => true,
        'reviews' => true,
        'thesis_supplements' => true
    ];
}
