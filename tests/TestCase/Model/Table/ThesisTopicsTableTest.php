<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThesisTopicsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThesisTopicsTable Test Case
 */
class ThesisTopicsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ThesisTopicsTable
     */
    public $ThesisTopics;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.thesis_topics',
        'app.external_consultants',
        'app.internal_consultants',
        'app.thesis_types',
        'app.failed_topic_suggestions',
        'app.theses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ThesisTopics') ? [] : ['className' => ThesisTopicsTable::class];
        $this->ThesisTopics = TableRegistry::getTableLocator()->get('ThesisTopics', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThesisTopics);

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
