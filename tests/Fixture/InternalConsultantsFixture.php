<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InternalConsultantsFixture
 *
 */
class InternalConsultantsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'department_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'internal_consultant_position_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_internal_consultants_departments_idx' => ['type' => 'index', 'columns' => ['department_id'], 'length' => []],
            'FK_internal_consultants_users_idx' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'FK_internal_consultants_internal_consultant_positions_idx' => ['type' => 'index', 'columns' => ['internal_consultant_position_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_internal_consultants_departments' => ['type' => 'foreign', 'columns' => ['department_id'], 'references' => ['departments', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_internal_consultants_internal_consultant_positions' => ['type' => 'foreign', 'columns' => ['internal_consultant_position_id'], 'references' => ['internal_consultant_positions', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_internal_consultants_users' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
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
                'name' => 'Lorem ipsum dolor sit amet',
                'created' => '2019-02-22 17:20:59',
                'modified' => '2019-02-22 17:20:59',
                'department_id' => 1,
                'internal_consultant_position_id' => 1,
                'user_id' => 1
            ],
        ];
        parent::init();
    }
}
