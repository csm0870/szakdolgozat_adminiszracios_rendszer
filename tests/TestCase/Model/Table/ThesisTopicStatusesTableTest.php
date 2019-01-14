<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThesisTopicStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThesisTopicStatusesTable Test Case
 */
class ThesisTopicStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ThesisTopicStatusesTable
     */
    public $ThesisTopicStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.thesis_topic_statuses',
        'app.thesis_topics'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ThesisTopicStatuses') ? [] : ['className' => ThesisTopicStatusesTable::class];
        $this->ThesisTopicStatuses = TableRegistry::getTableLocator()->get('ThesisTopicStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThesisTopicStatuses);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
