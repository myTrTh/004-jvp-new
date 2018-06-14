<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\User;
use App\Model\Role;
use App\Model\Upload;
use App\Model\Nach;
use App\Model\Achive;
use App\Model\Activity;
use App\Model\Cup;
use App\Model\Permission;
use App\Utils\RoleManager;

class AdminController extends Controller
{
	public function index()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$error = '';

		$this->container['db'];
		$users = Activity::orderBy('updated_at', 'desc')->get();

		$role_user = Role::where('role', 'ROLE_USER')->first();

		return $this->render('admin/index.html.twig', [
			'users' => $users,
			'error' => $error,
			'role_user' => $role_user
		]);
	}

	public function permissions($id)
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

		if ($request->get('submit_set_user_roles')) {

			$error = $this->container['adminManager']->setRoles($user->id, $request);

			if ($error === null) {
				$success = 'Роли успешно обновлены.';
			}
		}

		if ($request->get('submit_set_user_permissions')) {

			$error = $this->container['adminManager']->setPermissions($user->id, $request);

			if ($error === null) {
				$success = 'Разрешения успешно обновлены.';
			}
		}		

		return $this->render('admin/permissions.html.twig', [
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

	public function upload()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$error = '';

		$request = Request::createFromGlobals();
		if ($request->get('submit_upload_image')) {

			if ($request->files->get('userfile')) {

				$error = $this->container['adminManager']->addImage($request);

				if ($error === null)
					return $this->redirectToRoute('admin_upload');
			} else {
				$error = "Вы не выбрали изображение";
			}
		}

		if ($request->get('submit_delete_image')) {

			$error = $this->container['adminManager']->deleteImage($request);

			if ($error === null)
				return $this->redirectToRoute('admin_upload');
		}

		$logo = Upload::where('type', 'logo')->orderBy('id', 'desc')->get();
		$achive = Upload::where('type', 'achive')->orderBy('id', 'desc')->get();
		$cup = Upload::where('type', 'cup')->orderBy('id', 'desc')->get();

		return $this->render('admin/upload.html.twig', [
			'error' => $error,
			'logos' => $logo,
			'achives' => $achive,
			'cups' => $cup
		]);
	}

	public function achives()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];
		$error = '';
		
		$request = Request::createFromGlobals();
		if ($request->get('submit_add_achive')) {
			$error = $this->container['adminManager']->addAchive($request);

			if ($error === null)
				return $this->redirectToRoute('admin_achives');
		}

		if ($request->get('submit_delete_achive')) {
			$error = $this->container['adminManager']->deleteAchive($request);

			if ($error === null)
				return $this->redirectToRoute('admin_achives');			
		}

		$achives = Achive::orderBy('id', 'desc')->get();
		$users = User::orderBy('username', 'asc')->get();
		$upload = Upload::where('type', 'achive')->orderBy('id', 'desc')->get();

		return $this->render('admin/achives.html.twig', [
			'error' => $error,
			'achives' => $achives,
			'users' => $users,
			'upload' => $upload
		]);
	}

	public function cups()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$error = '';

		$request = Request::createFromGlobals();
		if ($request->get('submit_add_cups')) {
			$error = $this->container['adminManager']->addCups($request);

			if ($error === null)
				return $this->redirectToRoute('admin_cups');
		}

		if ($request->get('submit_delete_cups')) {
			$error = $this->container['adminManager']->deleteCups($request);

			if ($error === null)
				return $this->redirectToRoute('admin_cups');			
		}

		$cups = Cup::orderBy('id', 'desc')->get();
		$users = User::orderBy('username', 'asc')->get();
		$upload = Upload::where('type', 'cup')->orderBy('id', 'desc')->get();

		return $this->render('admin/cups.html.twig', [
			'error' => $error,
			'cups' => $cups,
			'users' => $users,
			'upload' => $upload
		]);
	}

	public function nach()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$request = Request::createFromGlobals();

		$error = '';
		$lastTitle = $request->get('title');
		$lastDescription = $request->get('description');
		$lastNumber = $request->get('number');

		if ($request->get('submit_add_nach')) {
			$error = $this->container['adminManager']->addNach($request);

			if ($error === null)
				return $this->redirectToRoute('admin_nach');
		}		

		$nachs = Nach::orderBy('message_id', 'desc')->get();

		return $this->render('admin/nach.html.twig', [
			'error' => $error,
			'nachs' => $nachs,
			'lastTitle' => $lastTitle,
			'lastNumber' => $lastNumber,
			'lastDescription' => $lastDescription
		]);
	}
}