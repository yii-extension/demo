<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class ResendCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/registration/resend');
    }

    public function testResendPage(AcceptanceTester $I): void
    {
        $I->see('Please fill out the following fields Resend confirmation message.');
        $I->see('Continue', '#form-registration-resend');
    }

    public function testResendEmptyDataTest(AcceptanceTester $I): void
    {
        $I->click('Continue', '#form-registration-resend');

        $I->expectTo('see validations errors.');
        $I->see('Value cannot be blank.');
    }

    public function testResendSubmitFormWrongData(AcceptanceTester $I): void
    {
        $I->fillField('#resend-email', 'noExist');

        $I->click('Continue', '#form-registration-resend');

        $I->expectTo('see validations errors.');
        $I->see('This value is not a valid email address.');

        $I->fillField('#resend-email', 'noExist@example.com');

        $I->click('Continue', '#form-registration-resend');

        $I->expectTo('see validations errors.');
        $I->see('Email not registered.');
    }

    public function testResendUserIsActive(AcceptanceTester $I): void
    {
        $I->fillField('#resend-email', 'administrator@example.com');

        $I->click('Continue', '#form-registration-resend');

        $I->expectTo('see validations errors.');
        $I->see('User is active.');
    }
}
