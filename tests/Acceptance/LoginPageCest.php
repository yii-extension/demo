<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class LoginPageCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/auth/login');
    }

    public function testAuthLoginPage(AcceptanceTester $I): void
    {
        $I->expectTo('see login page.');
        $I->see('Login.');
        $I->see('Please fill out the following.');
    }

    public function testAuthLoginEmptyDataTest(AcceptanceTester $I): void
    {
        $I->click('Login', '#form-security-login');

        $I->expectTo('see validations errors.');
        $I->see('Value cannot be blank.');
        $I->see('Value cannot be blank.');
        $I->see('Login', '#form-security-login');
    }

    public function testAuthLoginSubmitFormWrongData(AcceptanceTester $I): void
    {
        $I->fillField('#login-login', 'admin1');
        $I->fillField('#login-password', '1234567');

        $I->click('Login', '#form-security-login');

        $I->expectTo('see validations errors.');
        $I->see('Unregistered user/Invalid password.');
        $I->see('Login', '#form-security-login');
    }

    /**
     * @depends App\Tests\Acceptance\RegisterPageCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testAuthLoginUsernameSubmitFormSuccessData(AcceptanceTester $I): void
    {
        $I->fillField('#login-login', 'admin');
        $I->fillField('#login-password', '123456');

        $I->click('Login', '#form-security-login');

        $I->expectTo('see logged index page.');
        $I->seeLink('admin');
    }

    /**
     * @depends App\Tests\Acceptance\RegisterPageCest:testRegisterSuccessDataDefaultAccountConfirmationFalse
     */
    public function testAuthLoginEmailSubmitFormSuccessData(AcceptanceTester $I): void
    {
        $I->fillField('#login-login', 'administrator@example.com');
        $I->fillField('#login-password', '123456');

        $I->click('Login', '#form-security-login');

        $I->expectTo('see logged index page.');
        $I->seeLink('admin');
    }

    public function testAuthLoginSettingsPasswordRecoveryTrue(AcceptanceTester $I): void
    {
        $I->expectTo('see link forgot password');
        $I->seeLink('Forgot Password');
    }
}
