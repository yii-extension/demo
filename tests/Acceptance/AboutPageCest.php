<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class AboutPageCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/about');
    }

    public function testAboutPage(AcceptanceTester $I): void
    {
        $I->expectTo('see about page.');
        $I->see('This is the About page. You may modify the following file to customize its content.');
    }
}
