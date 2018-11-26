<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FailedTopicSuggestionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FailedTopicSuggestionsTable Test Case
 */
class FailedTopicSuggestionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FailedTopicSuggestionsTable
     */
    public $FailedTopicSuggestions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.failed_topic_suggestions',
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
        $config = TableRegistry::getTableLocator()->exists('FailedTopicSuggestions') ? [] : ['className' => FailedTopicSuggestionsTable::class];
        $this->FailedTopicSuggestions = TableRegistry::getTableLocator()->get('FailedTopicSuggestions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FailedTopicSuggestions);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
