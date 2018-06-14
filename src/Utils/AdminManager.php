<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User;
use App\Model\Role;
use App\Model\Upload;
use App\Model\Achive;
use App\Model\Cup;
use App\Model\Nach;

class AdminManager extends Manager
{
	public function setRoles($id, $request)
	{
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// prepare data
		$roles = $request->get('roles');

		if (count($roles) == 0)
			return 'У пользователя должна быть как минимум одна роль.';
		
		// if no ROLE_USER
		if (!in_array(1, $roles))
			return 'У пользователя обязательно должна быть роль ROLE_USER.';

		$user = User::where('id', $id)->first();

		if (!is_object($user) && !($user instanceof User))
			return 'Пользователь не найден.';

		$r = $user->roles->each(function ($role) use (&$roles, $user) {
			$id = $role['id'];
			
			if (in_array($id, $roles)) {
				$key = array_search($id, $roles);
				unset($roles[$key]);
			} else {
				// if trying delete ROLE_USER, ignore
				if ($id != 1) {
					$role = Role::where('id', $id)->first();
					$user->roles()->detach($role);
					$user->permissions()->detach($role->permissions);
				}
			}
		});

		if (count($roles) > 0) {
			foreach ($roles as $role) {
				$role = Role::where('id', $role)->first();
				$user->roles()->attach($role);
			}
		}
		return;
	}

	public function setPermissions($id, $request)
	{
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// prepare data
		$permissions = $request->get('permissions');

		$user = User::where('id', $id)->first();

		if (!is_object($user) && !($user instanceof User))
			return 'Пользователь не найден.';

		$user->permissions()->sync($permissions);

		return;
	}

	public function addImage($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$path = $request->get('image_type');
		if ($path != 'logo' && $path != 'achive' && $path != 'cup')
			return 'Выбран неправильный тип';

		$dir = '/images/tournaments/'.$path;

		$uploadedFile = $request->files->get('userfile');

		$file = $this->container['upload']->upload($uploadedFile, $dir, 150000, 200, 200);

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Пользователь не найден.';		


		if ($file[0]) {
			$upload = new Upload();
			$upload->type = $path;
			$upload->image = $file[1];
			$upload->user_id = $user->id;
			$upload->save();
		} else {
			return $file[1];
		}

		return;
	}

	public function deleteImage($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы.';

		$upload = $this->container['upload'];

		$id = $request->get('upload-image');

		$image = Upload::where('id', $id)->first();
		if (!is_object($image) && !($image instanceof Upload))
			return 'Изображение не найдено.';

		$dir = 'images/tournaments/'.$image->type;

		$upload->delete($image->image, $dir);

		$image->delete();

		return;
	}	

	public function addAchive($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$image_id = $request->get('image');
		$user_id = $request->get('user');
		$description = trim($request->get('description'));

		$user = User::where('id', $user_id)->first();
		if (!is_object($user) && !($user instanceof User))
			return 'Пользователь не найден.';
		
		$image = Upload::where('id', $image_id)->first();
		if (!is_object($image) && !($image instanceof Upload))
			return 'Изображение не найдено.';

		$achive = new Achive();
		$achive->user_id = $user->id;
		$achive->image_id = $image->id;
		$achive->description = $description;
		$achive->save();

	}

	public function deleteAchive($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$achive_id = $request->get('achive_id');

		$achive = Achive::where('id', $achive_id)->first();
		if (!is_object($achive) && !($achive instanceof Achive))
			return 'Запись не найдена.';
		
		$achive->delete();

	}

	public function addCups($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$image_id = $request->get('image');
		$user_id = $request->get('user');
		$description = trim($request->get('description'));

		$user = User::where('id', $user_id)->first();
		if (!is_object($user) && !($user instanceof User))
			return 'Пользователь не найден.';
		
		$image = Upload::where('id', $image_id)->first();
		if (!is_object($image) && !($image instanceof Upload))
			return 'Изображение не найдено.';

		$cup = new Cup();
		$cup->user_id = $user->id;
		$cup->image_id = $image->id;
		$cup->description = $description;
		$cup->save();

	}

	public function deleteCups($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$cup_id = $request->get('cup_id');

		$cup = Cup::where('id', $cup_id)->first();
		if (!is_object($cup) && !($cup instanceof Cups))
			return 'Запись не найдена.';
		
		$cup->delete();

	}	

	public function addNach($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$title = trim($request->get('title'));
		$description = trim($request->get('description'));
		$number = (int) $request->get('number');

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы';

		if ($error = $this->ifEmptyStringValidate($title, 'Заголовок'))
			return $error;

		if ($error = $this->ifEmptyStringValidate($description, 'Описание'))
			return $error;

		if ($error = $this->ifNumber($number))
			return $error;			

		$nach = new Nach();
		$nach->title = $title;
		$nach->description = $description;
		$nach->message_id = $number;
		$nach->author = $user->id;
		$nach->save();

	}	
}