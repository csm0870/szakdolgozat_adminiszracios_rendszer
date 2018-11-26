<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExternalConsultantsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExternalConsultantsTable Test Case
 */
class ExternalConsultantsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExternalConsultantsTable
     */
    public $ExternalConsultants;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.external_consultants',
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
        $config = TableRegistry::getTableLocator()->exists('ExternalConsultants') ? [] : ['className' => ExternalConsultantsTable::class];
        $this->ExternalConsultants = TableRegistry::getTableLocator()->get('ExternalConsultants', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExternalConsultants);

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
