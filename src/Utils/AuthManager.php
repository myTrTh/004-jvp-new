<?php

namespace App\Utils;

use App\Model\User;
use App\Model\Role;
use App\Model\ResetPassword;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Application;

class AuthManager extends Manager
{
	public function registration($request)
	{
		$this->container['db'];

		// prepare data
		$username = trim($request->get('username'));
		$email = trim($request->get('email'));
		$password = trim($request->get('password'));
		$repeat_password = trim($request->get('repeat_password'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->removeOldNotActiveAccount($username, $email))
			return $error;
		if ($error = $this->usernameValidate($username))
			return $error;
		if ($error = $this->ifExistUsernameValidate($username))
			return $error;
		if ($error = $this->emailValidate($email))
			return $error;
		if ($error = $this->ifExistEmailValidate($email))
			return $error;		
		if ($error = $this->passwordValidate($password))
			return $error;
		if ($error = $this->repeatPasswordValidate($repeat_password, $password))
			return $error;		

		$user = new User();
		$user->username = $username;
		$user->email = $email;
		$user->password = $this->encodePassword($password);

		$role_for_new_user = 1; 
		$role = Role::where('id', $role_for_new_user)->first();
		$permissions = $role->permissions;

		
		// if need confirmation registration
		if ($this->container['config']['auth']['registration_confirmation']) {

			$user->is_active = 0;
			$user->confirmation_token = $this->encodePassword(uniqid());
			$user->confirmation_datetime = new \DateTime();
			$user->save();
			$user->roles()->sync($role_for_new_user);
			$user->permissions()->sync($permissions);

			$mailer = $this->container['mailer'];
			$mailer->send('Регистрация', $user->email, 'email/registration.html.twig', $user->confirmation_token);

		} else {

			$user->is_active = 1;
			$user->confirmation_token = null;
			$user->confirmation_datetime = null;
			$user->save();
			$user->roles()->sync($role_for_new_user);
			$user->permissions()->sync($permissions);

			$remember_me = true;
			$this->setSession($user->id, $remember_me);
		}

		return;
	}

	public function login($request)
	{
		$this->container['db'];

		// prepare data
		$username = trim($request->get('username'));
		$password = trim($request->get('password'));
		$remember_me = (bool) trim($request->get('remember_me'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// validate data
		if ($error = $this->ifEmptyStringValidate($username, 'Логин'))
			return $error;
		if ($error = $this->ifEmptyStringValidate($password, 'Пароль'))
			return $error;

		// get user of rule login
		if ($this->container['config']['auth']['login'] == 'username') {
			$user = User::where('username', $username)
						->where('is_active', 1)->first();
		} else if ($this->container['config']['auth']['login'] == 'email') {
			$user = User::where('email', $username)
						->where('is_active', 1)->first();
		} else if ($this->container['config']['auth']['login'] == 'username.email') {
			$user = User::where('is_active', 1)
						->where( function ($query) use ($username) {
							$query->where('username', $username)
								  ->orWhere('email', $username);
						})->first();
		}

		// check password
		if ((!is_object($user) && !($user instanceof User)) or (!password_verify($password, $user->password))) {
			return 'Неверный логин или пароль.';
		}

		$this->setSession($user->id, $remember_me);

		return;
	}	

	public function logout()
	{
		$session = new Session();
		$session->invalidate();
	}

	public function registrationConfirmation($token)
	{
		$this->container['db'];

		$user = User::where('confirmation_token', $token)->first();
		
		if (!is_object($user) && !($user instanceof User))
			return 'Ошибка токена подтверждения.';

		if ($user->is_active == 1)
			return 'Токен подтверждения уже активирован.';

		if (strtotime($user->confirmation_datetime) < (time() - $this->container['config']['auth']['token_confirmation_time']))
			return 'Истек срок подтверждения токена.';

		$user->is_active = 1;
		$user->save();

		$remember_me = true;
		$this->setSession($user->id, $remember_me);
	}	

	public function checkResetPassword($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		$this->container['db'];

		// prepare data
		$username = trim($request->get('username'));


		$user = User::where('email', $username)
					->orWhere('username', $username)->first();

		if (!is_object($user) && !($user instanceof User))
			return 'Пользователя с таким логином или email нет.';

		$newpassword = new ResetPassword();
		$newpassword->user_id = $user->id;
		$newpassword->status = 0;
		$newpassword->confirmation_token = $this->encodePassword(uniqid());
		$newpassword->confirmation_datetime = new \DateTime();
		$newpassword->save();

		$mailer = $this->container['mailer'];
		$mailer->send('Восстановление пароля', $user->email, 'email/reset_password.html.twig', $newpassword->confirmation_token);		

		return;		
	}

	public function checkResetPasswordToken($token)
	{
		$this->container['db'];

		$reset = ResetPassword::where('confirmation_token', $token)->first();

		if (!$reset)
			return 'Неверная ссылка подтверждения';

		if ($reset->status == 1)
			return 'Ссылка на восстановления уже использована';

		if (strtotime($reset->confirmation_datetime) < (time() - $this->container['config']['auth']['token_confirmation_time']))
			return 'Истек срок ссылки на восстановление пароля';

		return;
	}

	public function resetSetNewPassword($request, $token)
	{
		$this->container['db'];

		// prepare data
		$password = trim($request->get('password'));
		$repeat_password = trim($request->get('repeat_password'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->passwordValidate($password))
			return $error;		
		if ($error = $this->repeatPasswordValidate($repeat_password, $password))
			return $error;

		$reset = ResetPassword::where('confirmation_token', $token)->first();

		if (!is_object($reset) && !($reset instanceof ResetPassword))
			return 'Ошибка при установке нового пароля';

		$reset->status = 1;
		$reset->save();

		$user = User::where('id', $reset->user_id)->first();
		$user->password = $this->encodePassword($password);
		$user->save();

		return;
	}

	public function removeOldNotActiveAccount($username, $email)
	{
		$this->container['db'];

		$user = User::where('is_active', 0)
					->where( function ($query) use ($username, $email) {
						$query->where('username', $username)
							  ->orWhere('email', $email);
					})->first();

		if (!is_object($user) && !($user instanceOf User))
			return;

		if ((strtotime($user->confirmation_datetime) + $this->container['config']['auth']['token_confirmation_time']) < time()) {
			$user->forceDelete();
		} else {
			return 'Вы уже зарегистрировались. Для активации аккаунта пройдите по ссылке, указанной в письме.';
		}

		return;
	}	
}