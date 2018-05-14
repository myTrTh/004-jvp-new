<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Permission;
use App\Utils\PermissionManager;

class PermissionController extends Controller
{
	public function list()
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$permission = Permission::all();

		return $this->render('permission/list.html.twig', [
			'permissions' => $permission
		]);
	}

	public function add()
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastPermission = trim($request->get('permission'));
		$lastDescription = trim($request->get('description'));

		if ($request->get('submit_permission_add')) {

			$error = $this->container['permissionManager']->add($request);

			if ($error === null)
				return $this->redirectToRoute('permission_list');

		}

		return $this->render('permission/add.html.twig', [
			'error' => $error,
			'lastPermission' => $lastPermission,
			'lastDescription' => $lastDescription
		]);
	}
	
	public function edit($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		// default values after submit
		$error = '';

		$permission = Permission::where('id', $id)->first();

		if (!is_object($permission) && !($permission instanceof Permission))
			$error = 'Такого разрешения не существует';

		$request = Request::createFromGlobals();

		if ($request->get('submit_permission_edit')) {

			$error = $this->container['permissionManager']->edit($id, $request);

			if ($error === null)
				return $this->redirectToRoute('permission_list');

		}

		return $this->render('permission/edit.html.twig', [
			'error' => $error,
			'permission' => $permission
		]);
	}

	public function delete($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		// default values after submit
		$error = '';

		$permission = Permission::where('id', $id)->first();

		if (!is_object($permission) && !($permission instanceof Permission))
			$error = 'Такого разрешения не существует';

		$request = Request::createFromGlobals();

		if ($request->get('submit_permission_delete')) {

			$error = $this->container['permissionManager']->delete($id, $request);

			if ($error === null)
				return $this->redirectToRoute('permission_list');

		}

		return $this->render('permission/delete.html.twig', [
			'error' => $error,
			'permission' => $permission
		]);
	}
}