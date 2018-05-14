<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User;
use App\Model\ResetPassword;
use Codeception\Util\ReflectionHelper;

class AuthManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $token;    
    
    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->container = $this->container->get();

        $this->token = 'token';

        $this->session = new Session();
        $this->session->set('csrf_token', $this->token);
    }

    protected function _after()
    {
    }

    // tests
    public function testRegistration()
    {
        $username = 'max_payne';
        $email = 'max_payne@gmail.com';
        $password = '1111';
        
        // test removeOLDaccount
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $user->is_active = 0;
        $user->confirmation_datetime = '2017-04-04 00:00:00';
        $user->save();

        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->tester->dontSeeInDatabase('users', array('username' => $username, 'email' => $email, 'is_active' => 1));

        $this->container['authManager']->registration($request);

        $this->tester->seeInDatabase('users', array('username' => $username, 'email' => $email, 'is_active' => 1));

        $user = User::where('username', $username)->first();
        $user->roles()->detach();
        $user->forceDelete();

        // test removeOLDaccount if new 
        $user = new User();
        $user->username = 'max_payne_no_active';
        $user->email = 'max_payne_no_active@gmail.com';
        $user->password = '1234';
        $user->is_active = 0;
        $user->confirmation_datetime = new \DateTime();
        $user->save();

        $request = new Request([
            'username' => 'max_payne_no_active',
            'email' => 'max_payne_no_active@gmail.com',
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertContains('Вы уже зарегистрировались. Для активации аккаунта пройдите по ссылке, указанной в письме.', $this->container['authManager']->registration($request));

        $user = User::where('username', 'max_payne_no_active')->first();
        $user->forceDelete();


        //
        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => 'wrong_token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => '',
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Логин не может быть пустым.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => 'ab',
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertContains('Длина логина может быть от', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => $username,
            'email' => '',
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Email не может быть пустым.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => $username,
            'email' => 'wrongemail@df',
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Неверный формат email.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => '',
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пароль не может быть пустым.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => '12',
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertContains('Пароль не может быть короче', $this->container['authManager']->registration($request));        

        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'repeat_password' => '1234',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пароли не совпадают.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => 'john_smith',
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пользователь с таким логином уже есть.', $this->container['authManager']->registration($request));

        $request = new Request([
            'username' => $username,
            'email' => 'john_smith@gmail.com',
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пользователь с таким email уже есть.', $this->container['authManager']->registration($request));        
    }

    public function testRegistrationWithConfirmation()
    {
        $this->container['config'] = [
                            'auth_rule' => [
                                'username-min'              => 3,
                                'username-max'              => 18,
                                'password-min'              => 4
                            ],
                            'auth'      => [
                                'registration_confirmation' => true,
                                'token_confirmation_time'   => 84600,
                                'lifetime'                  => 84600,
                                'lifetime_long'             => 2592000,
                                'login'                     => 'username.email'
                            ],
                            'session'       => [
                                'save_path'                 => __DIR__.'/../var/sessions/',
                                'cookie_lifetime'           => 0,
                                'cookie_httponly'           => 1,
                                'gc_maxlifetime'            => 2592000,
                                'gc_probability'            => 1,
                                'gc_divisor'                => 10
                            ]
                        ];

        $username = 'max_payne_confirm';
        $email = 'max_payne_confirm@gmail.com';
        $password = '1111';

        $request = new Request([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'repeat_password' => $password,
            '_csrf_token' => $this->token
        ]);

        $this->tester->dontSeeInDatabase('users', array('username' => $username, 'email' => $email));

        $this->container['authManager']->registration($request);

        $this->tester->seeInDatabase('users', array('username' => $username, 'email' => $email, 'is_active' => 0));

        $user = User::where('username', $username)->first();
        $user->roles()->detach();        
        $user->forceDelete();        
    }

    public function testSetSession()
    {
        $user_id = 2;

        $this->container['authManager']->setSession($user_id);

        $session = new Session();

        $this->assertEquals($user_id, $session->get('user_id'));
    }

    public function testEncodePassword()
    {
        $password = '1111';
        $wrong_password = '1234';

        $hash = $this->container['authManager']->encodePassword($password);

        $this->assertTrue(password_verify($password, $hash));
        
        $this->assertFalse(password_verify($wrong_password, $hash));
    }

    public function testLogin()
    {
        $request = new Request([
            'username' => 'john_smith',
            'password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertNull($this->container['authManager']->login($request));

        $request = new Request([
            'username' => 'john_smith',
            'password' => '1111',
            '_csrf_token' => 'wrong token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['authManager']->login($request));

        $request = new Request([
            'username' => 'john_smith',
            'password' => '1234',
            '_csrf_token' => $this->token
        ]);

        $this->assertContains('Неверный логин или пароль', $this->container['authManager']->login($request));

        $request = new Request([
            'username' => '',
            'password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Логин не может быть пустым.', $this->container['authManager']->login($request));

        $request = new Request([
            'username' => 'john_smith',
            'password' => '',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пароль не может быть пустым.', $this->container['authManager']->login($request));


        // username login
        $this->container['config'] = [
                            'auth_rule' => [
                                'username-min'              => 3,
                                'username-max'              => 18,
                                'password-min'              => 4
                            ],
                            'auth'      => [
                                'registration_confirmation' => false,
                                'token_confirmation_time'   => 84600,
                                'lifetime'                  => 84600,
                                'lifetime_long'             => 2592000,
                                'login'                     => 'username'
                            ],
                            'session'       => [
                                'save_path'                 => __DIR__.'/../var/sessions/',
                                'cookie_lifetime'           => 0,
                                'cookie_httponly'           => 1,
                                'gc_maxlifetime'            => 2592000,
                                'gc_probability'            => 1,
                                'gc_divisor'                => 10
                            ]
                        ];

        $request = new Request([
            'username' => 'john_smith',
            'password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertNull($this->container['authManager']->login($request));

        // username email
        $this->container['config'] = [
                            'auth_rule' => [
                                'username-min'              => 3,
                                'username-max'              => 18,
                                'password-min'              => 4
                            ],
                            'auth'      => [
                                'registration_confirmation' => false,
                                'token_confirmation_time'   => 84600,
                                'lifetime'                  => 84600,
                                'lifetime_long'             => 2592000,
                                'login'                     => 'email'
                            ],
                            'session'       => [
                                'save_path'                 => __DIR__.'/../var/sessions/',
                                'cookie_lifetime'           => 0,
                                'cookie_httponly'           => 1,
                                'gc_maxlifetime'            => 2592000,
                                'gc_probability'            => 1,
                                'gc_divisor'                => 10
                            ]
                        ];

        $request = new Request([
            'username' => 'john_smith@gmail.com',
            'password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertNull($this->container['authManager']->login($request));
    }

    public function testLogout()
    {
        $request = new Request([
            'username' => 'john_smith',
            'password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->container['authManager']->login($request);

        $session = new Session();

        $this->assertNotNull($session->get('user_id'));

        $this->container['authManager']->logout();

        $session = new Session();
        
        $this->assertNull($session->get('user_id'));
    }

    public function testRegistrationConfirmation()
    {
        $token = 'token_old';
        $this->assertEquals('Истек срок подтверждения токена.', $this->container['authManager']->registrationConfirmation($token));

        $token = 'token_already';
        $this->assertEquals('Токен подтверждения уже активирован.', $this->container['authManager']->registrationConfirmation($token));

        $token = 'undefinedtoken';
        $this->assertEquals('Ошибка токена подтверждения.', $this->container['authManager']->registrationConfirmation($token));

        $user = new User();
        $user->username = 'user_token3';
        $user->email = 'user_token3@gmail.com';
        $user->password = '1111';
        $user->is_active = 0;
        $user->confirmation_token = 'token_good';
        $user->confirmation_datetime = new \DateTime();
        $user->save();

        $this->assertNull($this->container['authManager']->registrationConfirmation($user->confirmation_token));

        $user->forceDelete();
    }

    public function testCheckResetPassword()
    {
        $request = new Request([
            'username' => 'user_no_exist',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пользователя с таким логином или email нет.', $this->container['authManager']->checkResetPassword($request));

        $request = new Request([
            'username' => 'user_no_exist',
            '_csrf_token' => 'wrong token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['authManager']->checkResetPassword($request));

        $username = 'axl_rose@gmail.com';
        $request = new Request([
            'username' => $username,
            '_csrf_token' => $this->token
        ]);

        $user = User::where('email', $username)->first();

        $this->tester->dontSeeInDatabase('reset_passwords', array('user_id' => $user->id, 'status' => 0));

        $this->container['authManager']->checkResetPassword($request);

        $this->tester->seeInDatabase('reset_passwords', array('user_id' => $user->id, 'status' => 0));

        $resetPassword = ResetPassword::where('user_id', $user->id)->first();
        $resetPassword->delete();
    }

    public function testCheckResetPasswordToken()
    {
        $token = '$2y$10$/vXayAxtP/Vz.TiLb4zxmOikThw6/CEh6NeOZI7OdepJzyYJINVF.';
        $this->assertEquals('Ссылка на восстановления уже использована', $this->container['authManager']->checkResetPasswordToken($token));

        $token = 'sdfasfasdf';
        $this->assertEquals('Неверная ссылка подтверждения', $this->container['authManager']->checkResetPasswordToken($token));

        $token = '$2y$10$/vXayAxtP/Vz.TiLb4zxmOikThw6/CEh6NeOZI7OdepJzyYJINVa';
        $this->assertEquals('Истек срок ссылки на восстановление пароля', $this->container['authManager']->checkResetPasswordToken($token));

        $token = 'token002';
        $resetPassword = new ResetPassword();
        $resetPassword->user_id = 1;
        $resetPassword->status = 0;
        $resetPassword->confirmation_token = $token;
        $resetPassword->confirmation_datetime = new \DateTime();
        $resetPassword->save();

        $this->assertNull($this->container['authManager']->checkResetPasswordToken($token));

        $resetPassword->delete();
    }

    public function testResetSetNewPassword()
    {
        $token = 'some token';

        $request = new Request([
            'password' => '1111',
            'repeat_password' => '1111',
            '_csrf_token' => 'wrong token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['authManager']->resetSetNewPassword($request, $token));

        $request = new Request([
            'password' => '',
            'repeat_password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пароль не может быть пустым.', $this->container['authManager']->resetSetNewPassword($request, $token));

        $request = new Request([
            'password' => '1111',
            'repeat_password' => '1234',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Пароли не совпадают.', $this->container['authManager']->resetSetNewPassword($request, $token));

       $request = new Request([
            'password' => '1111',
            'repeat_password' => '1111',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Ошибка при установке нового пароля', $this->container['authManager']->resetSetNewPassword($request, $token));

        $token = 'token001';
        $resetPassword = new ResetPassword();
        $resetPassword->user_id = 1;
        $resetPassword->status = 0;
        $resetPassword->confirmation_token = $token;
        $resetPassword->confirmation_datetime = new \DateTime();
        $resetPassword->save();

        $this->assertNull($this->container['authManager']->resetSetNewPassword($request, $token));

        $resetPassword->delete();
    }

}