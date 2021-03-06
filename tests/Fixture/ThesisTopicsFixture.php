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
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'is_thesis' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'confidential' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'starting_year_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'starting_semester' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'expected_ending_year_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'expected_ending_semester' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'cause_of_no_external_consultant' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'external_consultant_name' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_consultant_workplace' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_consultant_position' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_consultant_email' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_consultant_phone_number' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_consultant_address' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'thesis_accepted' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'internal_consultant_grade' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'first_thesis_subject_failed_suggestion' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'cause_of_rejecting_thesis_supplements' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'internal_consultant_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'language_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'student_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'thesis_topic_status_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'offered_topic_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_degree_thesis_data_internal_constultants_idx' => ['type' => 'index', 'columns' => ['internal_consultant_id'], 'length' => []],
            'FK_thesis_topics_years_idx' => ['type' => 'index', 'columns' => ['starting_year_id'], 'length' => []],
            'FK_thesis_topics_students_idx' => ['type' => 'index', 'columns' => ['student_id'], 'length' => []],
            'FK_thesis_topics_ending_year_idx' => ['type' => 'index', 'columns' => ['expected_ending_year_id'], 'length' => []],
            'FK_thesis_topics_languages_idx' => ['type' => 'index', 'columns' => ['language_id'], 'length' => []],
            'FK_thesis_topics_thesis_topic_statuses_idx' => ['type' => 'index', 'columns' => ['thesis_topic_status_id'], 'length' => []],
            'FK_thesis_topics_offered_topics_idx' => ['type' => 'index', 'columns' => ['offered_topic_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_degree_thesis_data_internal_constultants' => ['type' => 'foreign', 'columns' => ['internal_consultant_id'], 'references' => ['internal_consultants', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_thesis_topics_ending_year' => ['type' => 'foreign', 'columns' => ['expected_ending_year_id'], 'references' => ['years', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_thesis_topics_languages' => ['type' => 'foreign', 'columns' => ['language_id'], 'references' => ['languages', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_thesis_topics_offered_topics' => ['type' => 'foreign', 'columns' => ['offered_topic_id'], 'references' => ['offered_topics', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_thesis_topics_starting_year' => ['type' => 'foreign', 'columns' => ['starting_year_id'], 'references' => ['years', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_thesis_topics_students' => ['type' => 'foreign', 'columns' => ['student_id'], 'references' => ['students', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'FK_thesis_topics_thesis_topic_statuses' => ['type' => 'foreign', 'columns' => ['thesis_topic_status_id'], 'references' => ['thesis_topic_statuses', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
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
                'is_thesis' => 1,
                'confidential' => 1,
                'starting_year_id' => 1,
                'starting_semester' => 1,
                'expected_ending_year_id' => 1,
                'expected_ending_semester' => 1,
                'cause_of_no_external_consultant' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'external_consultant_name' => 'Lorem ipsum dolor sit amet',
                'external_consultant_workplace' => 'Lorem ipsum dolor sit amet',
                'external_consultant_position' => 'Lorem ipsum dolor sit amet',
                'external_consultant_email' => 'Lorem ipsum dolor sit amet',
                'external_consultant_phone_number' => 'Lorem ipsum dolor sit amet',
                'external_consultant_address' => 'Lorem ipsum dolor sit amet',
                'thesis_accepted' => 1,
                'internal_consultant_grade' => 1,
                'first_thesis_subject_failed_suggestion' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'cause_of_rejecting_thesis_supplements' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'deleted' => 1,
                'created' => '2019-02-28 21:22:02',
                'modified' => '2019-02-28 21:22:02',
                'internal_consultant_id' => 1,
                'language_id' => 1,
                'student_id' => 1,
                'thesis_topic_status_id' => 1,
                'offered_topic_id' => 1
            ],
        ];
        parent::init();
    }
}
