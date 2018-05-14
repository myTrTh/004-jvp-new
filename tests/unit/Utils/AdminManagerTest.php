<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Role;

class AdminManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $roleManager;
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
    public function testSetRoles()
    {
        $request = new Request([
            'roles' => array(),
            '_csrf_token' => 'wrong token'
        ]);
        $id = 16;
        $this->assertEquals('CSRF Token is not valid', $this->container['adminManager']->setRoles($id, $request));

        $request = new Request([
            'roles' => array(),
            '_csrf_token' => $this->token
        ]);

        $id = 2;
        $role_id = 2;
        $this->assertEquals('У пользователя должна быть как минимум одна роль', $this->container['adminManager']->setRoles($id, $request));

        $this->tester->dontSeeInDatabase('role_user', array('role_id' => $role_id, 'user_id' => $id));
        $request = new Request([
            'roles' => array($role_id),
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['adminManager']->setRoles($id, $request));

        $this->tester->seeInDatabase('role_user', array('role_id' => $role_id, 'user_id' => $id));


        $request = new Request([
            'roles' => array(1),
            '_csrf_token' => $this->token
        ]);
        $this->container['adminManager']->setRoles($id, $request);

        $request = new Request([
            'roles' => array(1),
            '_csrf_token' => $this->token
        ]);
        $id = 999;
        $this->assertEquals('Пользователь не найден', $this->container['adminManager']->setRoles($id, $request));        
    }
}