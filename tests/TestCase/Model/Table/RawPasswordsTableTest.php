<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RawPasswordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RawPasswordsTable Test Case
 */
class RawPasswordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RawPasswordsTable
     */
    public $RawPasswords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.raw_passwords',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RawPasswords') ? [] : ['className' => RawPasswordsTable::class];
        $this->RawPasswords = TableRegistry::getTableLocator()->get('RawPasswords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RawPasswords);

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
