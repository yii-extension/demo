<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

final class RequestCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->amOnPage('/recovery/request');
    }

    public function testRequestUnconfirmedAccountInactiveUser(FunctionalTester $I): void
    {
        $I->amGoingTo('register fixture unconfirmed user.');
        $I->unconfirmedUser();

        $I->amGoingTo('fill form recovery request.');
        $I->fillField('#request-email', 'joe@example.com');
        $I->click('Request Password', '#form-recovery-request');

        $I->expectTo('see recovery request validation message.');
        $I->see('Inactive user.');
    }

    public function testRequestSuccessData(FunctionalTester $I): void
    {
        $I->amGoingTo('fill form recovery request.');
        $I->fillField('#request-email', 'administrator@example.com');
        $I->click('Request Password', '#form-recovery-request');

        $I->expectTo('see recovery request validation message.');
        $I->see('Please check your email to change your password.');
    }
}
