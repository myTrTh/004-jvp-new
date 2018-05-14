<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Role;
use App\Model\Permission;
use App\Utils\RoleManager;

class RoleController extends Controller
{
	public function list()
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$roles = Role::all();

		return $this->render('role/list.html.twig', [
			'roles' => $roles
		]);
	}

	public function show($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$error = '';

		$this->container['db'];		
		$role = Role::where('id', $id)->first();

		$permissions = Permission::all();

		$request = Request::createFromGlobals();

		if ($request->get('submit_set_role_permissions')) {

			$error = $this->container['roleManager']->setPermissions($role->id, $request);
		}

		return $this->render('role/permission.html.twig', [
			'role' => $role,
			'permissions' => $permissions,
			'error' => $error
		]);
	}		

	public function add()
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastRole = trim($request->get('role'));

		if ($request->get('submit_role_add')) {

			$error = $this->container['roleManager']->add($request);

			if ($error === null)
				return $this->redirectToRoute('role_list');

		}

		return $this->render('role/add.html.twig', [
			'error' => $error,
			'lastRole' => $lastRole
		]);
	}
	
	public function edit($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		// default values after submit
		$error = '';

		$role = Role::where('id', $id)->first();

		if (!is_object($role) && !($role instanceof Role))
			$error = 'Такой роли не существует';

		$request = Request::createFromGlobals();

		if ($request->get('submit_role_edit')) {

			$error = $this->container['roleManager']->edit($id, $request);

			if ($error === null)
				return $this->redirectToRoute('role_list');

		}

		return $this->render('role/edit.html.twig', [
			'error' => $error,
			'role' => $role
		]);
	}

	public function delete($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		// default values after submit
		$error = '';

		$role = Role::where('id', $id)->first();

		if (!is_object($role) && !($role instanceof Role))
			$error = 'Такой роли не существует';

		$request = Request::createFromGlobals();

		if ($request->get('submit_role_delete')) {

			$error = $this->container['roleManager']->delete($id, $request);

			if ($error === null)
				return $this->redirectToRoute('role_list');

		}

		return $this->render('role/delete.html.twig', [
			'error' => $error,
			'role' => $role
		]);
	}
}