<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Role;
use App\Model\RoleUser;
use App\Model\PermissionUser;

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

        $this->container['db'];

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
        // WRONG CSRF TOKEN
        $request = new Request([
            'roles' => array(),
            '_csrf_token' => 'wrong token'
        ]);
        $id = 16;
        $this->assertEquals('CSRF Token is not valid', $this->container['adminManager']->setRoles($id, $request));

        // SET EMPTY ROLES
        $request = new Request([
            'roles' => array(),
            '_csrf_token' => $this->token
        ]);

        $id = 2;
        $role_id = 2;
        $this->assertEquals('У пользователя должна быть как минимум одна роль.', $this->container['adminManager']->setRoles($id, $request));

        // SET ROLES
        $id = 2;
        $role_id = 3;
        $this->tester->dontSeeInDatabase('role_user', array('role_id' => $role_id, 'user_id' => $id));
        $request = new Request([
            'roles' => array($role_id),
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['adminManager']->setRoles($id, $request));

        $this->tester->seeInDatabase('role_user', array('role_id' => $role_id, 'user_id' => $id));

        // clean set roles //
        $role_user = RoleUser::where('role_id', $role_id)->where('user_id', $id);
        $role_user->delete();

        // NO USER
        $request = new Request([
            'roles' => array(1),
            '_csrf_token' => $this->token
        ]);
        $id = 999;
        $this->assertEquals('Пользователь не найден.', $this->container['adminManager']->setRoles($id, $request));

        // DELETE ROLE
        $id = 2;
        $role_id = 1;
       $request = new Request([
            'roles' => array($role_id),
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['adminManager']->setRoles($id, $request));

        // restore role //
        $role_user = new RoleUser();
        $role_user->user_id = $id;
        $role_user->role_id = $role_id;
        $role_user->save();

        // DETACH ROLES
        $id = 1;
        $roles = array(1, 2);
       $request = new Request([
            'roles' => $roles,
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['adminManager']->setRoles($id, $request));

        // restore role //
        $role_user3 = new RoleUser();
        $role_user3->user_id = $id;
        $role_user3->role_id = 3;
        $role_user3->save();

        $role_user4 = new RoleUser();
        $role_user4->user_id = $id;
        $role_user4->role_id = 4;
        $role_user4->save();        
    }

    public function testSetPermission()
    {
        // WRONG CSRF TOKEN
        $request = new Request([
            'permissions' => array(),
            '_csrf_token' => 'wrong token'
        ]);
        $id = 16;
        $this->assertEquals('CSRF Token is not valid', $this->container['adminManager']->setPermissions($id, $request));

        // NO USER
        $request = new Request([
            'permissions' => array(1),
            '_csrf_token' => $this->token
        ]);
        $id = 999;
        $this->assertEquals('Пользователь не найден.', $this->container['adminManager']->setPermissions($id, $request));        

       // SET PERMISSIONS
        $id = 2;
        $permission_one = 1;
        $permission_two = 2;
        $this->tester->dontSeeInDatabase('permission_user', array('permission_id' => $permission_one, 'user_id' => $id));
        $this->tester->dontSeeInDatabase('permission_user', array('permission_id' => $permission_two, 'user_id' => $id));
        $request = new Request([
            'permissions' => array($permission_one, $permission_two),
            '_csrf_token' => $this->token
        ]);
        $this->assertNull($this->container['adminManager']->setPermissions($id, $request));
        $this->tester->seeInDatabase('permission_user', array('permission_id' => $permission_one, 'user_id' => $id));
        $this->tester->seeInDatabase('permission_user', array('permission_id' => $permission_two, 'user_id' => $id));

        // clean permissions
        $permission_user1 = PermissionUser::where('permission_id', $permission_one)->where('user_id', $id);
        $permission_user1->delete();

        $permission_user2 = PermissionUser::where('permission_id', $permission_two)->where('user_id', $id);
        $permission_user2->delete();        
    }
}