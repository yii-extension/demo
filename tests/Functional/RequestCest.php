<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use RuntimeException;
use App\Tests\FunctionalTester;

final class RequestCest
{
    public function testRequestUnconfirmedAccountInactiveUser(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');
        $I->unconfirmedUser();

        $I->amGoingTo('page recovery request');
        $I->amOnPage('/recovery/request');

        $I->amGoingTo('fill form recovery request.');
        $I->fillField('#request-email', 'joe@example.com');

        $I->click('Request Password', '#form-recovery-request');

        $I->expectTo('see recovery request validation message.');
        $I->see('Inactive user.');
    }

    public function testRequestSuccessData(FunctionalTester $I): void
    {
        $I->amGoingTo('page recovery request');
        $I->amOnPage('/recovery/request');

        $I->amGoingTo('fill form recovery request.');
        $I->fillField('#request-email', 'administrator@example.com');

        $I->click('Request Password', '#form-recovery-request');

        $I->expectTo('see recovery request validation message.');
        $I->see('Please check your email to change your password.');
    }

    public function testRequestSettingsPasswordRecoveryFalse(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings password recovery false');
        $I->updateInDatabase('module_settings', ['passwordRecovery' => false], ['id' => 1]);

        $I->amGoingTo('page recovery request');
        $I->amOnPage('/recovery/request');

        $I->see('Module password recovery user is disabled in the application configuration.');

        $I->amGoingTo('update settings password recovery true');
        $I->updateInDatabase('module_settings', ['passwordRecovery' => true], ['id' => 1]);
    }
}
