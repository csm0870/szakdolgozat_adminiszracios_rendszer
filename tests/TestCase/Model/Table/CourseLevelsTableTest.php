<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourseLevelsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseLevelsTable Test Case
 */
class CourseLevelsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseLevelsTable
     */
    public $CourseLevels;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.course_levels',
        'app.students'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CourseLevels') ? [] : ['className' => CourseLevelsTable::class];
        $this->CourseLevels = TableRegistry::getTableLocator()->get('CourseLevels', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CourseLevels);

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
