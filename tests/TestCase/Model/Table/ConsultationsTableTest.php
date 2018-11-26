<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConsultationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConsultationsTable Test Case
 */
class ConsultationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConsultationsTable
     */
    public $Consultations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.consultations',
        'app.theses',
        'app.consultation_occasions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Consultations') ? [] : ['className' => ConsultationsTable::class];
        $this->Consultations = TableRegistry::getTableLocator()->get('Consultations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Consultations);

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
