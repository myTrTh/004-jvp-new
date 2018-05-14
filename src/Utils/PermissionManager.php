<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Permission;

class PermissionManager extends Manager
{
	public function add($request)
	{
		if (!$this->container['userManager']->isPermission('permission-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$permission = trim($request->get('permission'));
		$description = trim($request->get('description'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->ifExistPermissionValidate($permission))
			return $error;

		$newpermission = new Permission();
		$newpermission->permission = $permission;
		$newpermission->description = $description;
		$newpermission->save();

		return;
	}

	public function edit($id, $request)
	{
		if (!$this->container['userManager']->isPermission('permission-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$id = (int) $id;
		$permission = trim($request->get('permission'));
		$description = trim($request->get('description'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$editpermission = Permission::where('id', $id)->first();

		if (!is_object($editpermission) && !($editpermission instanceof Permission))
			return 'Такого разрешения не существует';

		$editpermission->permission = $permission;
		$editpermission->description = $description;
		$editpermission->save();

		return;
	}

	public function delete($id, $request)
	{
		if (!$this->container['userManager']->isPermission('permission-control'))
			return "Доступ к этой функции запрещен.";

		// prepare data
		$id = (int) $id;

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$deletepermission = Permission::where('id', $id)->first();

		if (!is_object($deletepermission) && !($deletepermission instanceof Permission))
			return 'Такого разрешения не существует';

		$deletepermission->delete();

		return;
	}	
}