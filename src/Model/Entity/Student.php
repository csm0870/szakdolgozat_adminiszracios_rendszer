<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Student Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $neptun
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $specialisation
 * @property int|null $final_exam_subjects_status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $course_id
 * @property int|null $course_level_id
 * @property int|null $course_type_id
 * @property int|null $user_id
 * @property int|null $final_exam_subject_internal_consultant_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\CourseLevel $course_level
 * @property \App\Model\Entity\CourseType $course_type
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\FinalExamSubject[] $final_exam_subjects
 * @property \App\Model\Entity\OfferedTopic[] $offered_topics
 * @property \App\Model\Entity\ThesisTopic[] $thesis_topics
 */
class Student extends Entity
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
        'name' => true,
        'address' => true,
        'neptun' => true,
        'email' => true,
        'phone_number' => true,
        'specialisation' => true,
        'final_exam_subjects_status' => true,
        'created' => true,
        'modified' => true,
        'course_id' => true,
        'course_level_id' => true,
        'course_type_id' => true,
        'user_id' => true,
        'final_exam_subject_internal_consultant_id' => true, //null - még nincsenek kiválasztva, 1 - feltöltve, 2 - véglegesítve, 3 - elfogadva, 4 - elutasítva
        'course' => true,
        'course_level' => true,
        'course_type' => true,
        'user' => true,
        'final_exam_subjects' => true,
        'offered_topics' => true,
        'thesis_topics' => true
    ];
}
