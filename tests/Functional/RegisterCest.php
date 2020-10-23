<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class RegisterCest
{
    public function testRequestSettingsRegisterFalse(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings register false');
        $I->updateInDatabase('module_settings', ['register' => false], ['id' => 1]);

        $I->amGoingTo('page registration register');
        $I->amOnPage('/registration/register');

        $I->see('Module registration register user is disabled in the application configuration.');

        $I->amGoingTo('update settings register true');
        $I->updateInDatabase('module_settings', ['register' => true], ['id' => 1]);
    }

    public function testRegisterSuccessDataDefaultAccountSettingsConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings confirmation true');
        $I->updateInDatabase('module_settings', ['confirmation' => true], ['id' => 1]);

        $I->amGoingTo('page registration register');
        $I->amOnPage('/registration/register');

        $I->fillField('#register-email', 'administrator1@example.com');
        $I->fillField('#register-username', 'admin1');
        $I->fillField('#register-password', '123456');

        $I->click('Register', '#form-registration-register');

        $I->expectTo('see registration register validation.');
        $I->see('Please check your email to activate your username.');
        $I->dontSeeLink('Register', '#form-registration-register');

        $I->amGoingTo('update settings confirmation false');
        $I->updateInDatabase('module_settings', ['confirmation' => false], ['id' => 1]);
    }
}
