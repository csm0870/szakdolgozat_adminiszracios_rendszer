<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OfferedTopicsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OfferedTopicsTable Test Case
 */
class OfferedTopicsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OfferedTopicsTable
     */
    public $OfferedTopics;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.offered_topics',
        'app.internal_consultants',
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
        $config = TableRegistry::getTableLocator()->exists('OfferedTopics') ? [] : ['className' => OfferedTopicsTable::class];
        $this->OfferedTopics = TableRegistry::getTableLocator()->get('OfferedTopics', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OfferedTopics);

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

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
