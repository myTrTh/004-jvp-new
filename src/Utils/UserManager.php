<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User;
use App\Model\Rate;

class UserManager extends Manager
{
	public function getUser()
	{
		$this->container['db'];

		$session = new Session();

		if (!$session->get('user_id'))
			return;

		$user = User::where('id', $session->get('user_id'))->first();

		if (!is_object($user) && !($user instanceof User))
			return;

		return $user;
	}

	public function isAccess($access_role)
	{
		if (in_array($access_role, array_keys($this->getRoles())))
			return true;
		else
			return false;
	}

	public function isUserAccess($id, $access_role)
	{
		$user = User::where('id', $id)->first();
		$roles = $user->roles->keyBy('role')->toArray();

		if (in_array($access_role, array_keys($roles)))
			return true;
		else
			return false;
	}

	public function isPermission($access_permission)
	{
		if (in_array($access_permission, array_keys($this->getPermissions())))
			return true;
		else
			return false;
	}

	public function isUserPermission($id, $access_permission)
	{
		$user = User::where('id', $id)->first();
		$permissions = $user->permissions->keyBy('permission')->toArray();

		if (in_array($access_permission, array_keys($permissions)))
			return true;
		else
			return false;
	}

	public function changePassword($request)
	{
		$this->container['db'];
		
		// prepare data
		$password = trim($request->get('password'));
		$newpassword = trim($request->get('newpassword'));
		$repeat_password = trim($request->get('repeat_password'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$session = new Session();
		if (!$session->get('user_id'))
			return 'Вы не авторизированы.';		

		if ($error = $this->passwordValidate($newpassword))
			return $error;

		if ($error = $this->repeatPasswordValidate($repeat_password, $newpassword))
			return $error;


		$user = User::where('id', $session->get('user_id'))->first();

		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		if (!password_verify($password, $user->password))
			return 'Неверный текущий пароль.';		

		$user->password = $this->encodePassword($repeat_password);
		$user->save();

		return;
	}

	public function getRoles()
	{
		$this->container['db'];

		$session = new Session();

		$user = User::where('id', $session->get('user_id'))->first();

		if (!is_object($user) && !($user instanceof User))
			return ['ROLE_NO' => 'ROLE_NO'];

		return $user->roles->keyBy('role')->toArray();
	}

	public function getUserRoles($id)
	{
		$this->container['db'];

		$user = User::where('id', $id)->first();

		if (!is_object($user) && !($user instanceof User))
			return ['ROLE_NO' => 'ROLE_NO'];

		return $user->roles->keyBy('role')->toArray();
	}	

	public function getPermissions()
	{
		$this->container['db'];

		$session = new Session();

		$user = User::where('id', $session->get('user_id'))->first();

		if (!is_object($user) && !($user instanceof User))
			return ['no_permissions' => 'no_permissions'];

		return $user->permissions->keyBy('permission')->toArray();
	}	

	public function addImage($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$dir = '/images/users';

		$uploadedFile = $request->files->get('userfile');

		$upload = $this->container['upload'];
		$file = $upload->upload($uploadedFile, $dir, 150000, 200, 200);

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		if ($file[0]) {
			$oldimage = $user->image;
			$upload->delete($oldimage, $dir);

			$user->image = $file[1];
			$user->save();
		} else {
			return $file[1];
		}

		return;
	}

	public function deleteImage($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$dir = 'images/users';

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		$upload = $this->container['upload'];

		$oldimage = $user->image;
		if ($oldimage)
			$upload->delete($oldimage, $dir);
		else
			return 'Изображение не установлено';

		$user->image = null;
		$user->save();

		return;
	}

	public function setTimezone($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$timezone = $request->get('user_timezone');

		$tzlist = $this->container['dater']->timeZoneShortList();
		$tzlist = array_flip($tzlist);
		if (!in_array($timezone, $tzlist))
			return 'Некорректное значение часового пояса';

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		$options = unserialize($user->options);
		$options['timezone'] = $timezone;
		$user->options = serialize($options);
		$user->save();
	}

	public function setNotification($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		$notification_guestbook = $request->get('guestbook');
		$notification_vote = $request->get('vote');

		$options = unserialize($user->options);
		$options['notification']['guestbook'] = $notification_guestbook;
		$options['notification']['vote'] = $notification_vote;
		$user->options = serialize($options);
		$user->save();
	}

	public function hierarchyAccess($id)
	{
		$hierarchy = $this->container['config']['role_hierarchy'];
		$roles1 = $this->getRoles();
		$roles2 = $this->getUserRoles($id);

		$ruler = 0;
		$user = 0;
		foreach ($hierarchy as $key => $value) {
			if (in_array($value, array_keys($roles1))) {
				if ($ruler < $key)
					$ruler = $key;
			}

			if (in_array($value, array_keys($roles2))) {
				if ($user < $key)
					$user = $key;
			}
		}

		if ($ruler > $user)
			return true;
		else
			return false;
	}

	public function getRate($author_id)
	{
		$my_rate_plus = Rate::selectRaw('user_id, count(sign) as signcount')->where('author_id', $author_id)->where('sign', '1')->groupBy('user_id')->orderBy('signcount', 'desc')->take(10)->get();
		$my_sum_plus = Rate::where('author_id', $author_id)->where('sign', '1')->count();
		$my_rate_minus = Rate::selectRaw('user_id, count(sign) as signcount')->where('author_id', $author_id)->where('sign', '-1')->groupBy('user_id')->orderBy('signcount', 'desc')->take(10)->get();
		$my_sum_minus = Rate::where('author_id', $author_id)->where('sign', '-1')->count();		

		$for_rate_plus = Rate::selectRaw('author_id, count(sign) as signcount')->where('user_id', $author_id)->where('sign', '1')->groupBy('author_id')->orderBy('signcount', 'desc')->take(10)->get();		
		$for_rate_minus = Rate::selectRaw('author_id, count(sign) as signcount')->where('user_id', $author_id)->where('sign', '-1')->groupBy('author_id')->orderBy('signcount', 'desc')->take(10)->get();
		$for_sum_plus = Rate::where('user_id', $author_id)->where('sign', '1')->count();		
		$for_sum_minus = Rate::where('user_id', $author_id)->where('sign', '-1')->count();		

		$rate = [
			"my_plus" => $my_rate_plus,
			"for_plus" => $for_rate_plus,
			'my_plus_count' => $my_sum_plus,
			'for_plus_count' => $for_sum_plus,
			"my_minus" => $my_rate_minus,
			"for_minus" => $for_rate_minus,
			"my_minus_count" => $my_sum_minus,
			"for_minus_count" => $for_sum_minus
		];
		return $rate;
	}
}