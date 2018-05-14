<?php


class AuthCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function loginTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'jack_daniels');
        $I->fillField('password', '1111');
        $I->click('Вход');
        $I->see('Вы вошли как jack_daniels');
    }

    public function noLoginTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('username', 'user_nobody');
        $I->fillField('password', '1234');
        $I->click('Вход');
        $I->see('Неверный логин или пароль.');
    }

    public function registrationAndResetPasswordTest(AcceptanceTester $I)
    {
        $user_id = 9;

        $I->amOnPage('/registration');

        $I->fillField('username', 'ivan_drago');
        $I->fillField('email', 'ivan_drago@gmail.com');
        $I->fillField('password', '1111');
        $I->fillField('repeat_password', '1111');
        $I->click('Регистрация');
        $I->see('Вы вошли как ivan_drago');

        $I->amOnPage('/logout');

        $I->dontSeeInDatabase('reset_passwords', array('user_id' => $user_id, 'status' => 0));

        $I->amOnPage('/reset-password');
        $I->fillField('username', 'ivan_drago');
        $I->click('Сбросить пароль');
        $I->see('На Вашу почту отправлено письмо со ссылкой на восстановление пароля');

        $I->seeInDatabase('reset_passwords', array('user_id' => $user_id, 'status' => 0));        
    }

}
