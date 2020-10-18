<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class ContactCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->amOnPage('/contact');
    }

    public function testOpenContactPage(FunctionalTester $I): void
    {
        $I->see('Contact.');
        $I->see('Please fill out the following.');
    }

    public function testSubmitEmptyForm(FunctionalTester $I): void
    {
        $I->submitForm('#form-contact', []);

        $I->expectTo('see validations errors');
        $I->see('Contact.');
        $I->see('Please fill out the following.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
    }

    public function testSubmitFormWithIncorrectEmail(FunctionalTester $I): void
    {
        $I->submitForm('#form-contact', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);

        $I->expectTo('see that email address is wrong');
        $I->see('This value is not a valid email address.');
    }

    public function testSubmitFormSuccessfully(FunctionalTester $I): void
    {
        $I->submitForm('#form-contact', [
            'ContactForm[username]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
        ]);
        $I->see("Thanks to contact us, we'll get in touch with you as soon as possible.");
    }
}
