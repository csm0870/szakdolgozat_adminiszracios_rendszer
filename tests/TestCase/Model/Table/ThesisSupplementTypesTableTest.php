<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThesisSupplementTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThesisSupplementTypesTable Test Case
 */
class ThesisSupplementTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ThesisSupplementTypesTable
     */
    public $ThesisSupplementTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.thesis_supplement_types',
        'app.thesis_supplements'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ThesisSupplementTypes') ? [] : ['className' => ThesisSupplementTypesTable::class];
        $this->ThesisSupplementTypes = TableRegistry::getTableLocator()->get('ThesisSupplementTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThesisSupplementTypes);

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
