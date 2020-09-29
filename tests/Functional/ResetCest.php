<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class ResetCest
{
    public function testResetPasswordWithEmptyQueryParams(FunctionalTester $I)
    {
        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/recovery/reset');

        $I->amGoingTo('see recovery reset validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testResetPasswordWrongId(FunctionalTester $I)
    {
        $I->amGoingTo('register fixture user recovery.');
        $I->recoveryResetUser();
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/recovery/reset/8/' . $token[0]);

        $I->amGoingTo('see recovery reset validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testResetPasswordWrongCode(FunctionalTester $I)
    {
        $I->amGoingTo('register fixture user recovery.');
        $I->recoveryResetUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id');

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/recovery/reset/' . $id[0] . '/6f5d0dad53ef73e6ba6f01a441c0e601');

        $I->amGoingTo('see recovery reset validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testResetPasswordWithTokenExpired(FunctionalTester $I)
    {
        $I->amGoingTo('register fixture user with token expired.');
        $I->recoveryTokenExpiredUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id', ['user_id' => 6]);
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/recovery/reset/' . $id[0] . '/' . $token[0]);

        $I->amGoingTo('see recovery reset validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testResetPasswordSuccessData(FunctionalTester $I)
    {
        $I->amGoingTo('register fixture user recovery.');
        $I->recoveryResetUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id');
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/recovery/reset/' . $id[0] . '/' . $token[0]);

        $I->fillField('#reset-password', 'newpass');
        $I->click('Reset Password', '#form-recovery-reset');

        $I->amGoingTo('see recovery reset validation message.');
        $I->see('Your password has been changed.');
    }
}
