<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Role;

class RoleManagerTest extends \Codeception\Test\Unit
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
    public function testAdd()
    {
        $request = new Request([
            'role' => 'ROLE_NEW_USER',
            '_csrf_token' => 'wrong token'
        ]);
        $this->assertEquals('CSRF Token is not valid', $this->container['roleManager']->add($request));

        $request = new Request([
            'role' => '',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Роль не может быть пустой.', $this->container['roleManager']->add($request));

        $request = new Request([
            'role' => 'WRONG_FORMAT_ROLE',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Роль должна начинаться с ROLE_ и содержать заглавные латинские буквы и цифры/знак поддерживания', $this->container['roleManager']->add($request));

        $request = new Request([
            'role' => 'ROLE_RIGHT',
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['roleManager']->add($request));

        $this->tester->seeInDatabase('roles', array('role' => 'ROLE_RIGHT'));

        $role = Role::where('role', 'ROLE_RIGHT')->first();
        $role->forceDelete();

        $request = new Request([
            'role' => 'ROLE_USER',
            '_csrf_token' => $this->token
        ]);
        $this->assertEquals('Такая роль уже есть.', $this->container['roleManager']->add($request));

    }

    public function testEdit()
    {
        $request = new Request([
            'role' => 'ROLE_NEW_USER',
            '_csrf_token' => 'wrong token'
        ]);
        $id = 2;
        $this->assertEquals('CSRF Token is not valid', $this->container['roleManager']->edit($id, $request));

        $request = new Request([
            'role' => '',
            '_csrf_token' => $this->token
        ]);
        $id = 2;
        $this->assertEquals('Роль не может быть пустой.', $this->container['roleManager']->edit($id, $request));

        $request = new Request([
            'role' => 'ROLE_NOBODY',
            '_csrf_token' => $this->token
        ]);
        $id = 10;
        $this->assertEquals('Такой роли не существует', $this->container['roleManager']->edit($id, $request));

        $request = new Request([
            'role' => 'ROLE_NEW_ADMIN',
            '_csrf_token' => $this->token
        ]);
        $id = 2;
        $this->assertNull($this->container['roleManager']->edit($id, $request));

        $role = Role::where('id', 2)->first();
        $role->role = 'ROLE_ADMIN';
        $role->save();
    }

    public function testDelete()
    {
        $request = new Request([
            'role' => 'ROLE_NEW_USER',
            '_csrf_token' => 'wrong token'
        ]);
        $id = 2;
        $this->assertEquals('CSRF Token is not valid', $this->container['roleManager']->delete($id, $request));

        $request = new Request([
            'role' => 'ROLE_NOBODY',
            '_csrf_token' => $this->token
        ]);
        $id = 10;
        $this->assertEquals('Такой роли не существует', $this->container['roleManager']->delete($id, $request));

        $role = new Role();
        $role->role = 'ROLE_FOR_DELETE';
        $role->save();

        $request = new Request([
            'role' => 'ROLE_FOR_DELETE',
            '_csrf_token' => $this->token
        ]);
        $id = $role->id;
        $this->assertNull($this->container['roleManager']->delete($id, $request));

        $role->forceDelete();
    }
}