<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Role;

class RoleManager extends Manager
{
	public function add($request)
	{
		if (!$this->container['userManager']->isPermission('role-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$role = trim($request->get('role'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->roleValidate($role))
			return $error;

		if ($error = $this->ifExistRoleValidate($role))
			return $error;

		$newrole = new Role();
		$newrole->role = $role;
		$newrole->save();

		return;
	}

	public function edit($id, $request)
	{
		if (!$this->container['userManager']->isPermission('role-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$id = (int) $id;
		$role = trim($request->get('role'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->roleValidate($role))
			return $error;

		$editrole = Role::where('id', $id)->first();

		if (!is_object($editrole) && !($editrole instanceof Role))
			return 'Такой роли не существует';

		$editrole->role = $role;
		$editrole->save();

		return;
	}

	public function delete($id, $request)
	{
		if (!$this->container['userManager']->isPermission('role-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$id = (int) $id;

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$deleterole = Role::where('id', $id)->first();

		if (!is_object($deleterole) && !($deleterole instanceof Role))
			return 'Такой роли не существует';

		$deleterole->delete();

		return;
	}

	public function setPermissions($id, $request)
	{
		if (!$this->container['userManager']->isPermission('role-control'))
			return "Доступ к этой функции запрещен.";

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// prepare data
		$permissions = $request->get('permissions');

		$role = Role::where('id', $id)->first();

		if (!is_object($role) && !($role instanceof Role))
			return 'Роль не найдена';

		$role->permissions()->sync($permissions);

		return;
	}	
}