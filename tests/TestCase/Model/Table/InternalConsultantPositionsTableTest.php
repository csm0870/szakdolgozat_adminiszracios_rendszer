<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InternalConsultantPositionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InternalConsultantPositionsTable Test Case
 */
class InternalConsultantPositionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InternalConsultantPositionsTable
     */
    public $InternalConsultantPositions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.internal_consultant_positions',
        'app.internal_consultants'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InternalConsultantPositions') ? [] : ['className' => InternalConsultantPositionsTable::class];
        $this->InternalConsultantPositions = TableRegistry::getTableLocator()->get('InternalConsultantPositions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InternalConsultantPositions);

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
