<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StudentsFixture
 *
 */
class StudentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'address' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'neptun' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone_number' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'specialisation' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'first_thesis_subject_completed' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'course_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_level_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'thesis_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_studens_courses_idx' => ['type' => 'index', 'columns' => ['course_id'], 'length' => []],
            'FK_studens_course_types_idx' => ['type' => 'index', 'columns' => ['course_type_id'], 'length' => []],
            'FK_studens_course_levels_idx' => ['type' => 'index', 'columns' => ['course_level_id'], 'length' => []],
            'FK_studets_degree_thesis_data_idx' => ['type' => 'index', 'columns' => ['thesis_id'], 'length' => []],
            'FK_studens_users_idx' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'neptun_UNIQUE' => ['type' => 'unique', 'columns' => ['neptun'], 'length' => []],
            'FK_studens_course_levels' => ['type' => 'foreign', 'columns' => ['course_level_id'], 'references' => ['course_levels', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_course_types' => ['type' => 'foreign', 'columns' => ['course_type_id'], 'references' => ['course_types', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_courses' => ['type' => 'foreign', 'columns' => ['course_id'], 'references' => ['courses', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_users' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'FK_studets_degree_thesis_data' => ['type' => 'foreign', 'columns' => ['thesis_id'], 'references' => ['thesis_topics', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
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
                'address' => 'Lorem ipsum dolor sit amet',
                'neptun' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'phone_number' => 'Lorem ipsum dolor sit amet',
                'specialisation' => 'Lorem ipsum dolor sit amet',
                'first_thesis_subject_completed' => 1,
                'created' => '2018-11-21 16:13:48',
                'modified' => '2018-11-21 16:13:48',
                'course_id' => 1,
                'course_level_id' => 1,
                'course_type_id' => 1,
                'thesis_id' => 1,
                'user_id' => 1
            ],
        ];
        parent::init();
    }
}
