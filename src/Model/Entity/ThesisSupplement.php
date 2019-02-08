<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ThesisSupplement Entity
 *
 * @property int $id
 * @property string|null $file
 * @property int|null $thesis_topic_id
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\ThesisTopic $thesis_topic
 */
class ThesisSupplement extends Entity
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
        'file' => true,
        'thesis_topic_id' => true,
        'created' => true,
        'thesis_topic' => true
    ];
}
