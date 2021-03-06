<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class IndexPageCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/');
    }

    public function testIndexPage(AcceptanceTester $I): void
    {
        $I->expectTo('see page index.');
        $I->see('Hello World');
    }
}
