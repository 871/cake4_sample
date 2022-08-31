<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\CustomerRegistrationForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\CustomerRegistrationForm Test Case
 */
class CustomerRegistrationFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\CustomerRegistrationForm
     */
    protected $CustomerRegistration;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->CustomerRegistration = new CustomerRegistrationForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CustomerRegistration);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\CustomerRegistrationForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
