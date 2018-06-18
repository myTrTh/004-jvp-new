<?php

namespace App\Controller;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Model\User;

class UserController extends Controller
{
	public function list($sort)
	{
		$this->container['db'];
		
		// default sort
		$condition = 'username';
		$order = 'asc';		

		if ($sort == 'alpha_desc') { $condition = 'username'; $order = 'desc'; }
		if ($sort == 'since_asc') { $condition = 'created_at'; $order = 'asc'; }
		if ($sort == 'since_desc') { $condition = 'created_at'; $order = 'desc'; }

		$users = User::orderBy($condition, $order)->get();

		return $this->render('user/list.html.twig', [
			'users' => $users,
			'sort' => $sort
		]);
	}

	public function show($id)
	{
		$this->container['db'];

		$user = User::where('id', $id)->first();

		return $this->render('user/profile.html.twig', array(
			'user' => $user
		));
	}

	public function profile()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User)) {
			$session = new Session();
			$session->set('page_return', 'profile');
			return $this->redirectToRoute('auth_login');
		}

		return $this->render('user/profile.html.twig', array(
			'user' => $user
		));
	}	

	public function settings()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User)) {
			$session = new Session();
			$session->set('page_return', 'settings');
			return $this->redirectToRoute('auth_login');
		}

		$error = '';

		$request = Request::createFromGlobals();
		if ($request->get('submit_upload_image')) {

			if ($request->files->get('userfile')) {

				$error = $this->container['userManager']->addImage($request);

				if ($error === null)
					return $this->redirectToRoute('settings');
			} else {
				$error = "Вы не выбрали изображение";
			}
		}

		if ($request->get('submit_delete_image')) {

			$error = $this->container['userManager']->deleteImage($request);

			if ($error === null)
				return $this->redirectToRoute('settings');
		}

		if ($request->get('submit_notification')) {
			$error = $this->container['userManager']->setNotification($request);

			if ($error === null)
				return $this->redirectToRoute('settings');
		}

		if ($request->get('submit_timezone')) {
			$error = $this->container['userManager']->setTimezone($request);

			if ($error === null)
				return $this->redirectToRoute('settings');
		}

		$options = unserialize($user->options);
		$user->options = $options;

		$timezones = $this->container['dater']->timeZoneShortList();

		return $this->render('user/settings.html.twig', array(
			'user' => $user,
			'timezones' => $timezones,
			'error' => $error
		));		
	}

	public function changePassword()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User)) {
			$session = new Session();
			$session->set('page_return', 'user_change_password');
			return $this->redirectToRoute('auth_login');
		}

		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$success = '';

		if ($request->get('submit_change_password')) {

			$userManager = $this->container['userManager'];
			$error = $userManager->changePassword($request);

			if ($error === null)
				$success = 'Пароль успешно изменен.';
		}

		return $this->render('user/change_password.html.twig', [
			'error' => $error,
			'success' => $success
		]);
	}
}