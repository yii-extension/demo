<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\AcceptanceTester;

final class ContactPageCest
{
    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage('/contact');
    }

    public function testContactPage(AcceptanceTester $I): void
    {
        $I->see('Please fill out the following to Contact.');
    }

    public function testContactFormCanBeSubmitted(AcceptanceTester $I): void
    {
        $I->amGoingTo('submit contact form with correct data');
        $I->fillField('#contactform-username', 'tester');
        $I->fillField('#contactform-email', 'tester@example.com');
        $I->fillField('#contactform-subject', 'test subject');
        $I->fillField('#contactform-body', 'test content');

        $I->click('contact-button');

        $I->dontSeeElement('#contact-form');
        $I->see("Thanks to contact us, we'll get in touch with you as soon as possible.");
    }
}
