<?php


class AccessCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function userTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'user_user');
        $I->fillField('password', '1111');
        $I->click('Вход');

        $I->amOnPage('/admin/users');
        $I->see('ERROR 403');

        $I->amOnPage('/roles');
        $I->see('ERROR 403');
    }

    public function adminTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'user_admin');
        $I->fillField('password', '1111');
        $I->click('Вход');

        $I->amOnPage('/admin/users');
        $I->see('Админка: пользователи');

        $I->amOnPage('/roles');
        $I->see('error 403');
    }

    public function superAdminTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'user_super_admin');
        $I->fillField('password', '1111');
        $I->click('Вход');

        $I->amOnPage('/admin/users');
        $I->see('Админка: пользователи');

        $I->amOnPage('/roles');
        $I->see('Список ролей');
    }     
}