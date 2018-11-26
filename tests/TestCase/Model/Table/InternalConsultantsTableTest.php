<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InternalConsultantsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InternalConsultantsTable Test Case
 */
class InternalConsultantsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InternalConsultantsTable
     */
    public $InternalConsultants;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.internal_consultants',
        'app.departments',
        'app.users',
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
        $config = TableRegistry::getTableLocator()->exists('InternalConsultants') ? [] : ['className' => InternalConsultantsTable::class];
        $this->InternalConsultants = TableRegistry::getTableLocator()->get('InternalConsultants', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InternalConsultants);

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
