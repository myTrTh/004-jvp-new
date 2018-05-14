<?php


class ErrorCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function error403Test(AcceptanceTester $I)
    {
        $I->amOnPage('/error/403');
        $I->see('ERROR 403', 'h4');
    }

    public function error404Test(AcceptanceTester $I)
    {
        $I->amOnPage('/error/404');
        $I->see('ERROR 404', 'h4');
    }

    public function error500Test(AcceptanceTester $I)
    {
        $I->amOnPage('/error/500');
        $I->see('ERROR 500', 'h4');
    }
}
