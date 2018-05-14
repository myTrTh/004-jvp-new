<?php


class UserCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function profileTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'john_smith');
        $I->fillField('password', '1111');
        $I->click('Вход');

        $I->amOnPage('/profile');
        $I->see('Логин: john_smith');
    }

    public function changePasswordTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'john_smith');
        $I->fillField('password', '1111');
        $I->click('Вход');
        $I->see('Вы вошли как john_smith');

        $I->amOnPage('/profile/change-password');
        $I->fillField('newpassword', '2222');
        $I->fillField('repeat_password', '2222');
        $I->fillField('password', '1111');
        $I->click('Изменить пароль');

        $I->amOnPage('/logout');
        $I->amOnPage('/login');

        $I->fillField('username', 'john_smith');
        $I->fillField('password', '2222');
        $I->click('Вход');
        $I->see('Вы вошли как john_smith');

        // return to start password
        $I->amOnPage('/profile/change-password');
        $I->fillField('newpassword', '1111');
        $I->fillField('repeat_password', '1111');
        $I->fillField('password', '2222');
        $I->click('Изменить пароль');     
    }
}
