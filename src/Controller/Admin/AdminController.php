<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\User;
use App\Model\Role;
use App\Model\Permission;
use App\Utils\RoleManager;

class AdminController extends Controller
{
	public function index()
	{
		$error = '';
		
		$roles = $this->container['userManager']->getRoles();
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];
		$users = User::all();

		$roles = Role::all();
		$role_user = Role::where('role', 'ROLE_USER')->first();
		$permissions_user = $role_user->permissions;

		$role_moderator = Role::where('role', 'ROLE_MODERATOR')->first();
		$permissions_moderator = $role_moderator->permissions;

		$role_admin = Role::where('role', 'ROLE_ADMIN')->first();
		$permissions_admin = $role_admin->permissions;

		$role_super_admin = Role::where('role', 'ROLE_SUPER_ADMIN')->first();
		$permissions_super_admin = $role_super_admin->permissions;			

		return $this->render('admin/index.html.twig', [
			'users' => $users,
			'roles' => $roles,
			'permissions_user' => $permissions_user,
			'permissions_moderator' => $permissions_moderator,
			'permissions_admin' => $permissions_admin,
			'permissions_super_admin' => $permissions_super_admin,
			'error' => $error			
		]);
	}

	public function ajax_permissions()
	{
		$request = Request::createFromGlobals();
		$id = $request->get('user');
		$token = $request->get('_csrf_token');
		$permissions = explode(",", $request->get('permissions'));
		$request = new Request([
			'permissions' => $permissions,
			'_csrf_token' => $token
		]);
		$result = $this->container['adminManager']->setPermissions($id, $request);
		if (!$result) {
			$result = [
				'success' => 1,
				'message' => 'Разрешения успешно установлены.'
			];
		}

		return new Response(json_encode($result));
	}

	public function ajax_roles()
	{
		$request = Request::createFromGlobals();
		$id = $request->get('user');
		$token = $request->get('_csrf_token');
		$roles = explode(",", $request->get('roles'));
		$request = new Request([
			'roles' => $roles,
			'_csrf_token' => $token
		]);
		$result = $this->container['adminManager']->setRoles($id, $request);
		if (!$result) {
			$result = [
				'success' => 1,
				'message' => 'Роли успешно установлены.'
			];
		}

		return new Response(json_encode($result));
	}

	public function user($id)
	{
		$roles = $this->container['userManager']->getRoles();
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$error = '';
		$success = '';

		$this->container['db'];		
		$user = User::where('id', $id)->first();

		$roles = Role::all();
		$role_user = Role::where('role', 'ROLE_USER')->first();
		$permissions_user = $role_user->permissions;

		$role_moderator = Role::where('role', 'ROLE_MODERATOR')->first();
		$permissions_moderator = $role_moderator->permissions;

		$role_admin = Role::where('role', 'ROLE_ADMIN')->first();
		$permissions_admin = $role_admin->permissions;

		$role_super_admin = Role::where('role', 'ROLE_SUPER_ADMIN')->first();
		$permissions_super_admin = $role_super_admin->permissions;		

		$request = Request::createFromGlobals();

		if ($request->get('submit_set_user_role')) {

			$error = $this->container['adminManager']->setRoles($user->id, $request);

			if ($error === null) {
				$success = 'Роли успешно обновлены.';
			}
		}

		if ($request->get('submit_set_user_permission')) {

			$error = $this->container['adminManager']->setPermissions($user->id, $request);

			if ($error === null) {
				$success = 'Разрешения успешно обновлены.';
			}
		}		

		return $this->render('admin/user_control.html.twig', [
			'user' => $user,
			'success' => $success,
			'roles' => $roles,
			'permissions_user' => $permissions_user,
			'permissions_moderator' => $permissions_moderator,
			'permissions_admin' => $permissions_admin,
			'permissions_super_admin' => $permissions_super_admin,
			'error' => $error
		]);
	}
}