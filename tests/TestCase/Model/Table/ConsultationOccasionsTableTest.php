<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConsultationOccasionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConsultationOccasionsTable Test Case
 */
class ConsultationOccasionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConsultationOccasionsTable
     */
    public $ConsultationOccasions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.consultation_occasions',
        'app.consultations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ConsultationOccasions') ? [] : ['className' => ConsultationOccasionsTable::class];
        $this->ConsultationOccasions = TableRegistry::getTableLocator()->get('ConsultationOccasions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConsultationOccasions);

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
