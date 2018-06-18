<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use App\Model\User;
use App\Model\Role;
use App\Model\Guestbook;
use App\Model\Adminbook;
use App\Model\Permission;
use App\Model\Tournament;

class Manager
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function encodePassword(string $password)
	{
		return password_hash($password, PASSWORD_DEFAULT);		
	}

	public function setSession(int $user_id, bool $remember_me = false)
	{
		if ($remember_me)
			$lifetime = $this->container['config']['auth']['lifetime_long'];
		else
			$lifetime = $this->container['config']['auth']['lifetime'];

		$session = new Session();
		$session->migrate(true, $lifetime);
		$session->set('user_id', $user_id);
	}

	public function usernameValidate(?string $username)
	{
		if (empty($username))
			return 'Логин не может быть пустым.';

		if ((mb_strlen($username) < $this->container['config']['auth_rule']['username-min']) or (mb_strlen($username) > $this->container['config']['auth_rule']['username-max']))
			return 'Длина логина может быть от '.$this->container['config']['auth_rule']['username-min'].' до '.$this->container['config']['auth_rule']['username-max'].' символов.';

		return;
	}

	public function emailValidate(?string $email)
	{
		if (empty($email))
			return 'Email не может быть пустым.';

		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			return 'Неверный формат email.';

		return;
	}

	public function passwordValidate(?string $password)
	{
		if (empty($password))
			return 'Пароль не может быть пустым.';

		if (mb_strlen($password) < $this->container['config']['auth_rule']['password-min']) {
			return 'Пароль не может быть короче '.$this->container['config']['auth_rule']['password-min'].' символов. Рекомендуем создавать сложные пароли.';
		}

		return;		
	}

	public function repeatPasswordValidate(?string $repeat_password, string $password)
	{
		if (empty($repeat_password))
			return 'Повторный пароль не может быть пустым.';

		if ($repeat_password !== $password)
			return 'Пароли не совпадают.';

		return;		
	}

	public function ifExistUsernameValidate(string $username)
	{
		$this->container['db'];

		$user = User::where('username', $username)
					->where('is_active', 1)->first();
		
		if (is_object($user) && $user instanceOf User)
			return 'Пользователь с таким логином уже есть.';

		return;
	}

	public function ifExistEmailValidate(string $email)
	{
		$this->container['db'];
		
		$user = User::where('email', $email)->first();
		
		if (is_object($user) && $user instanceOf User)
			return 'Пользователь с таким email уже есть.';

		return;
	}

	public function ifEmptyStringValidate(?string $string, $field = null)
	{
		if (empty($string)) {
			if ($field === null)
				$out = 'Поле';
			else
				$out = $field;
			
			return $out.' не может быть пустым.';
		}

		return;
	}

	public function roleValidate(? string $rolename)
	{
		if (empty($rolename))
			return 'Роль не может быть пустой.';

		if (!preg_match('/^ROLE_[A-Z_0-9]+/', $rolename))
			return 'Роль должна начинаться с ROLE_ и содержать заглавные латинские буквы и цифры/знак подчеркивания';

		return;
	}

	public function ifExistRoleValidate($rolename)
	{
		$this->container['db'];

		$role = Role::where('role', $rolename)->first();
		
		if (is_object($role) && $role instanceof Role)
			return 'Такая роль уже есть.';

		return;
	}

	public function ifExistPermissionValidate($permissionname)
	{
		$this->container['db'];

		$permission = Permission::where('permission', $permissionname)->first();
		
		if (is_object($permission) && $permission instanceof Permission)
			return 'Такое разрешение уже есть.';

		return;
	}

	public function duplicate($message)
	{
		$this->container['db'];

		$user = $this->container['userManager']->getUser();

		$post = Guestbook::latest()->first();

		if (is_object($post) && $post->message == $message && $post->user_id == $user->id)
			return 'Вы уже отправляли этого сообщение.';

		return;
	}

	public function adminDuplicate($message)
	{
		$this->container['db'];

		$user = $this->container['userManager']->getUser();

		$post = Adminbook::latest()->first();

		if (is_object($post) && $post->message == $message && $post->user_id == $user->id)
			return 'Вы уже отправляли этого сообщение.';

		return;
	}	

	public function ifNumber($number)
	{
		if (!is_int($number))
			return 'Значение должно быть числом';

		if ($number <= 0)
			return 'Число должно быть положительным';
	}

	public function ifDublicate($name)
	{
		$this->container['db'];

		$tournament = Tournament::where('title', $name)->first();

		if (is_object($tournament) && $tournament instanceof Tournament)
			return 'Турнир с таким названием уже существует.';
	}
}