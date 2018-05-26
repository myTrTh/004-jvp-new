<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User;

class UserManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $token;
    private $session;

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
    public function testGetUser()
    {
        $this->assertNull($this->container['userManager']->getUser());

        $this->session->set('user_id', 1);
        
        $this->assertInstanceOf(User::class, $this->container['userManager']->getUser());

        $this->session->set('user_id', 999);

        $this->assertNull($this->container['userManager']->getUser());
    }

    public function testChangePassword()
    {
        $request = new Request([
            'password' => '1111',
            'newpassword' => '1234',
            'repeat_password' => '1111',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Вы не авторизированы.', $this->container['userManager']->changePassword($request));

        $this->session->set('user_id', 999);

        $request = new Request([
            'password' => '1111',
            'newpassword' => '1234',
            'repeat_password' => '1234',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Вы не авторизированы.', $this->container['userManager']->changePassword($request));

        $this->session->set('user_id', 1);

        $request = new Request([
            'password' => '1111',
            'newpassword' => '1234',
            'repeat_password' => '1234',
            '_csrf_token' => 'wrong token'
        ]);
        $this->assertEquals('CSRF Token is not valid', $this->container['userManager']->changePassword($request));

        $request = new Request([
            'password' => '1111',
            'newpassword' => '',
            'repeat_password' => '2222',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Пароль не может быть пустым.', $this->container['userManager']->changePassword($request));

        $request = new Request([
            'password' => '1111',
            'newpassword' => '2222',
            'repeat_password' => '',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Повторный пароль не может быть пустым.', $this->container['userManager']->changePassword($request));

        $request = new Request([
            'password' => 'wrong-password',
            'newpassword' => '1111',
            'repeat_password' => '1111',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Неверный текущий пароль.', $this->container['userManager']->changePassword($request));

        $request = new Request([
            'password' => '1111',
            'newpassword' => '1111',
            'repeat_password' => '1111',
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['userManager']->changePassword($request));
    }

    public function testGetRoles()
    {
        $this->assertArrayHasKey('ROLE_NO', $this->container['userManager']->getRoles());

        $this->session->set('user_id', 1);
        $this->assertArrayHasKey('ROLE_ADMIN', $this->container['userManager']->getRoles());
    }

    public function testGetUserRoles()
    {
        $user_id = 1;
        $this->assertArrayHasKey('ROLE_ADMIN', $this->container['userManager']->getUserRoles($user_id));

        $user_id = 999;
        $this->assertArrayHasKey('NO_ROLE', $this->container['userManager']->getUserRoles($user_id));        
    }    

    public function testIsAccess()
    {
        $this->session->set('user_id', 1);

        $this->assertFalse($this->container['userManager']->isAccess('ROLE_NO_ACCESS'));
        $this->assertTrue($this->container['userManager']->isAccess('ROLE_ADMIN'));
    }

    public function testIsUserAccess()
    {
        $user_id = 1;

        $this->assertFalse($this->container['userManager']->isUserAccess($user_id, 'ROLE_NO_ACCESS'));
        $this->assertTrue($this->container['userManager']->isUserAccess($user_id, 'ROLE_ADMIN'));
    }

    public function testIsPermission()
    {
        $this->session->set('user_id', 1);

        $this->assertFalse($this->container['userManager']->isPermission('guestbook-edit'));
        $this->assertTrue($this->container['userManager']->isPermission('guestbook-write'));
    }

    public function testIsUserPermission()
    {
        $user_id = 1;

        $this->assertFalse($this->container['userManager']->isUserPermission($user_id, 'guestbook-edit'));
        $this->assertTrue($this->container['userManager']->isUserPermission($user_id, 'guestbook-write'));
    }

    public function testHierarchyAccess()
    {
        $user_1_id = 1;
        $user_2_id = 2;
       
        $this->session->set('user_id', $user_1_id);

        $this->assertTrue($this->container['userManager']->hierarchyAccess($user_2_id));
        
        $this->session->set('user_id', $user_2_id);
        $this->assertFalse($this->container['userManager']->hierarchyAccess($user_1_id));        
    }
}