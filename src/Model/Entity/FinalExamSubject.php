<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FinalExamSubject Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $semester
 * @property string|null $teachers
 * @property int|null $student_id
 *
 * @property \App\Model\Entity\Student $student
 */
class FinalExamSubject extends Entity
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
        'semester' => true,
        'teachers' => true,
        'student_id' => true,
        'student' => true
    ];
}
