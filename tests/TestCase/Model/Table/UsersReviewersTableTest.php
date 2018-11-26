<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersReviewersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersReviewersTable Test Case
 */
class UsersReviewersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersReviewersTable
     */
    public $UsersReviewers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_reviewers',
        'app.users',
        'app.reviewers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsersReviewers') ? [] : ['className' => UsersReviewersTable::class];
        $this->UsersReviewers = TableRegistry::getTableLocator()->get('UsersReviewers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersReviewers);

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
