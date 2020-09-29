<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class ConfirmCest
{
    public function testRegistrationConfirmWithEmptyQueryParams(FunctionalTester $I): void
    {
        $I->amGoingTo('page recovery reset.');
        $I->amOnPage('/registration/confirm');

        $I->amGoingTo('see registration confirm validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testRegistationConfirmAccountConfirmationTrue(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');
        $I->unconfirmedUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id');
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/registration/confirm/' . $id[0] . '/' . $token[0]);

        $I->expectTo('see registration confirm validation message.');
        $I->see('Your user has been confirmed.');
    }

    public function testRegistationConfirmAccountWrongId(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');
        $I->unconfirmedUser();
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/registration/confirm/4/' . $token[0]);

        $I->expectTo('see registration confirm validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testRegistationConfirmAccountWrongCode(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');
        $I->unconfirmedUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id');
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/registration/confirm/' . $id[0] . '/NO2aCmBIjFQX624xmAc3VBu7Th3NJoa7');

        $I->expectTo('see registration confirm validation message.');
        $I->see('The requested page does not exist.');
    }

    public function testRegistationConfirmAccountWithTokenExpired(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user with token expired.');
        $I->unconfirmedTokenExpiredUser();
        $id = $I->grabColumnFromDatabase('token', 'user_id');
        $token = $I->grabColumnFromDatabase('token', 'code');

        $I->amGoingTo('page recovery confirm.');
        $I->amOnPage('/registration/confirm/' . $id[0] . '/' . $token[0]);

        $I->expectTo('see registration confirm validation message.');
        $I->see('The requested page does not exist.');
    }
}
