<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ThesisTopicsFixture
 *
 */
class ThesisTopicsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'starting_semester' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'language' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cause_of_no_external_consultant' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modeified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'accepted_by_internal_consultant' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'accepted_by_head_of_department' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'accepted_by_external_consultant' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'modifiable' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_thesis' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'external_consultant_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'internal_consultant_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'encrytped' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'thesis_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_degree_thesis_data_internal_constultants_idx' => ['type' => 'index', 'columns' => ['internal_consultant_id'], 'length' => []],
            'FK_degree_thesis_data_external_constultants_idx' => ['type' => 'index', 'columns' => ['external_consultant_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_degree_thesis_data_external_constultants' => ['type' => 'foreign', 'columns' => ['external_consultant_id'], 'references' => ['external_consultants', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'FK_degree_thesis_data_internal_constultants' => ['type' => 'foreign', 'columns' => ['internal_consultant_id'], 'references' => ['internal_consultants', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'starting_semester' => 'Lorem ipsum dolor sit amet',
                'language' => 'Lorem ipsum dolor sit amet',
                'cause_of_no_external_consultant' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2018-11-21 16:13:49',
                'modeified' => '2018-11-21 16:13:49',
                'accepted_by_internal_consultant' => 1,
                'accepted_by_head_of_department' => 1,
                'accepted_by_external_consultant' => 1,
                'modifiable' => 1,
                'deleted' => 1,
                'is_thesis' => 1,
                'external_consultant_id' => 1,
                'internal_consultant_id' => 1,
                'encrytped' => 1,
                'thesis_type_id' => 1
            ],
        ];
        parent::init();
    }
}
