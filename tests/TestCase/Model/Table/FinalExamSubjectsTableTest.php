<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FinalExamSubjectsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FinalExamSubjectsTable Test Case
 */
class FinalExamSubjectsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FinalExamSubjectsTable
     */
    public $FinalExamSubjects;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.final_exam_subjects',
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
        $config = TableRegistry::getTableLocator()->exists('FinalExamSubjects') ? [] : ['className' => FinalExamSubjectsTable::class];
        $this->FinalExamSubjects = TableRegistry::getTableLocator()->get('FinalExamSubjects', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FinalExamSubjects);

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
