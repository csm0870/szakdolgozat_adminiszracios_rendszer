<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property int|null $structure_and_style_point
 * @property string|null $cause_of_structure_and_style_point
 * @property int|null $processing_literature_point
 * @property string|null $cause_of_processing_literature_point
 * @property int|null $writing_up_the_topic_point
 * @property string|null $cause_writing_up_the_topic_point
 * @property int|null $practical_applicability_point
 * @property string|null $cause_of_practical_applicability
 * @property string|null $general_comments
 * @property int|null $grade
 * @property |null $confidentiality_contract
 * @property int|null $confidentiality_contract_status
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int|null $thesis_topic_id
 * @property int|null $reviewer_id
 *
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
 * @property \App\Model\Entity\Reviewer $reviewer
 * @property \App\Model\Entity\Question[] $questions
 */
class Review extends Entity
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
        'structure_and_style_point' => true,
        'cause_of_structure_and_style_point' => true,
        'processing_literature_point' => true,
        'cause_of_processing_literature_point' => true,
        'writing_up_the_topic_point' => true,
        'cause_writing_up_the_topic_point' => true,
        'practical_applicability_point' => true,
        'cause_of_practical_applicability' => true,
        'general_comments' => true,
        'grade' => true,
        'confidentiality_contract' => true,
        'confidentiality_contract_status' => true,
        'modified' => true,
        'created' => true,
        'thesis_topic_id' => true,
        'reviewer_id' => true,
        'thesis_topic' => true,
        'reviewer' => true,
        'questions' => true
    ];
}
