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
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'address' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'neptun' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone_number' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'specialisation' => ['type' => 'string', 'length' => 40, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'final_exam_subjects_status' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'course_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_level_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_type_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'final_exam_subjects_internal_consultant_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_studens_courses_idx' => ['type' => 'index', 'columns' => ['course_id'], 'length' => []],
            'FK_studens_course_types_idx' => ['type' => 'index', 'columns' => ['course_type_id'], 'length' => []],
            'FK_studens_course_levels_idx' => ['type' => 'index', 'columns' => ['course_level_id'], 'length' => []],
            'FK_studens_users_idx' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'FK_students_final_exam_internal_consultants_idx' => ['type' => 'index', 'columns' => ['final_exam_subjects_internal_consultant_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'neptun_UNIQUE' => ['type' => 'unique', 'columns' => ['neptun'], 'length' => []],
            'FK_studens_course_levels' => ['type' => 'foreign', 'columns' => ['course_level_id'], 'references' => ['course_levels', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_course_types' => ['type' => 'foreign', 'columns' => ['course_type_id'], 'references' => ['course_types', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_courses' => ['type' => 'foreign', 'columns' => ['course_id'], 'references' => ['courses', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_studens_users' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'FK_students_final_exam_internal_consultants' => ['type' => 'foreign', 'columns' => ['final_exam_subjects_internal_consultant_id'], 'references' => ['internal_consultants', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
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
                'neptun' => 'Lore',
                'email' => 'Lorem ipsum dolor sit amet',
                'phone_number' => 'Lorem ipsum d',
                'specialisation' => 'Lorem ipsum dolor sit amet',
                'final_exam_subjects_status' => 1,
                'created' => '2019-02-21 18:05:06',
                'modified' => '2019-02-21 18:05:06',
                'course_id' => 1,
                'course_level_id' => 1,
                'course_type_id' => 1,
                'user_id' => 1,
                'final_exam_subjects_internal_consultant_id' => 1
            ],
        ];
        parent::init();
    }
}
