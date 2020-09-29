<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class RegisterCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/registration/register');
    }

    public function testRegisterPage(AcceptanceTester $I): void
    {
        $I->see('Please fill out the following fields');
        $I->see('Sign up');
    }

    public function testRegisterSuccessDataDefaultAccountConfirmationFalse(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'administrator@example.com');
        $I->fillField('#registration-username', 'admin');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Your account has been created.');
        $I->dontSeeLink('Register', '#form-registration-register');
    }

    public function testRegisterEmptyData(AcceptanceTester $I): void
    {
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
        $I->see('Register', '#form-registration-register');
    }

    public function testRegisterWrongEmailData(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'register');
        $I->fillField('#registration-username', 'register');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('This value is not a valid email address.');
        $I->see('Register', '#form-registration-register');
    }

    public function testRegisterEmailExistData(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'administrator@example.com');
        $I->fillField('#registration-username', 'administrator');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Email already registered.');
        $I->see('Register', '#form-registration-register');
    }

    public function testsRegisterInvalidUsernameData(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'demo@example.com');
        $I->fillField('#registration-username', '**admin');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Value is invalid.');

        $I->wantTo('register invalid data.');
        $I->fillField('#registration-email', 'demo@example.com');
        $I->fillField('#registration-username', '**');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');


        $I->expectTo('see registration register validation.');
        $I->see('Username should contain at least 3 characters.');
        $I->see('Register', '#form-registration-register');
    }

    public function testRegisterUsernameExistData(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'demo@example.com');
        $I->fillField('#registration-username', 'admin');
        $I->fillField('#registration-password', '123456');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Username already registered.');
        $I->see('Register', '#form-registration-register');
    }

    public function testRegisterInvalidPasswordData(AcceptanceTester $I): void
    {
        $I->fillField('#registration-email', 'demo@example.com');
        $I->fillField('#registration-username', 'demo');
        $I->fillField('#registration-password', '123');
        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Password should contain at least 6 characters.');
        $I->see('Register', '#form-registration-register');
    }
}
