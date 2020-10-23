<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class LoginCest
{
    public function testAuthLoginSettingsPasswordRecoveryFalse(FunctionalTester $I): void
    {
        $I->amGoingTo('update settings password recovery false');
        $I->updateInDatabase('module_settings', ['passwordRecovery' => false], ['id' => 1]);

        $I->amGoingTo('page auth login');
        $I->amOnPage('/auth/login');

        $I->expectTo('dont see link forgot password');
        $I->dontSeeLink('Forgot Password');

        $I->updateInDatabase('module_settings', ['passwordRecovery' => true], ['id' => 1]);
    }
}
