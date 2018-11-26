<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ThesesFixture
 *
 */
class ThesesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'thesis_pdf' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'supplements' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'internal_consultant_grade' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'handed_in' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'accepted' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'review_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'thesis_topic_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_theses_thesis_topics_idx' => ['type' => 'index', 'columns' => ['thesis_topic_id'], 'length' => []],
            'FK_theses_reviews_idx' => ['type' => 'index', 'columns' => ['review_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_theses_reviews' => ['type' => 'foreign', 'columns' => ['review_id'], 'references' => ['reviews', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'FK_theses_thesis_topics' => ['type' => 'foreign', 'columns' => ['thesis_topic_id'], 'references' => ['thesis_topics', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
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
                'thesis_pdf' => 'Lorem ipsum dolor sit amet',
                'supplements' => 'Lorem ipsum dolor sit amet',
                'internal_consultant_grade' => 1,
                'handed_in' => 1,
                'accepted' => 1,
                'deleted' => 1,
                'review_id' => 1,
                'thesis_topic_id' => 1
            ],
        ];
        parent::init();
    }
}
