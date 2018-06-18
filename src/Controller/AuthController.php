<?php

namespace App\Controller;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthController extends Controller
{
	public function registration()
	{
		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastUsername = trim($request->get('username'));
		$lastEmail = trim($request->get('email'));

		if ($request->get('submit_registration')) {

			$auth = $this->container['authManager'];
			$error = $auth->registration($request);

			if ($error === null) {

				if ($this->container['config']['auth']['registration_confirmation']) {
			
					$session = new Session();
					$session->set('info', 'registration-send-email');

					return $this->redirectToRoute('auth_registration_send_email');
				} else {				
					return $this->redirectToRoute('user_profile');
				}
			}
		}

		return $this->render('auth/registration.html.twig', [
			'error' => $error,
			'lastUsername' => $lastUsername,
			'lastEmail' => $lastEmail
		]);
	}

	public function login()
	{
		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastUsername = trim($request->get('username'));

		if ($request->get('submit_login')) {

			$auth = $this->container['authManager'];
			$error = $auth->login($request);

			if ($error === null) {
				$session = new Session();

				if ($session->get('page_return') !== null) {
					return $this->redirectUrl($session->get('page_return'));
				} else {
					return $this->redirectToRoute('app_index');
				}
			}			
		}

		return $this->render('auth/login.html.twig', [
			'error' => $error,
			'lastUsername' => $lastUsername
		]);	
	}

	public function logout()
	{
		$auth = $this->container['authManager'];
		$auth->logout();

		return $this->redirectToRoute("app_index");
	}	

	public function registrationSendEmail()
	{
		$session = new Session();

		if ($session->get('info') !== null && $session->get('info') === 'registration-send-email') {

			$session->remove('info');
			return $this->render('auth/registration_send_email.html.twig');

		} else {
			return $this->redirectToRoute('app_index');
		}
	}

	public function registrationConfirmation($token)
	{
		$error = '';

		$auth = $this->container['authManager'];
		$error = $auth->registrationConfirmation($token);

		if ($error === null)
			return $this->redirectToRoute('app_index');

		return $this->render('auth/registration_confirmation.html.twig', [
			'error' => $error
		]);
	}

	public function resetPassword()
	{
		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastUsername = trim($request->get('username'));

		if ($request->get('submit_reset_password')) {

			$auth = $this->container['authManager'];
			$error = $auth->checkResetPassword($request);

			if ($error === null) {

				$session = new Session();
				$session->set('info', 'reset-password-send-email');

				return $this->redirectToRoute('auth_reset_password_send_email');
			}			
		}

		return $this->render('auth/reset_password.html.twig', [
			'lastUsername' => $lastUsername,
			'error' => $error
		]);
	}

	public function resetPasswordSendEmail()
	{
		$session = new Session();

		if ($session->get('info') !== null && $session->get('info') === 'reset-password-send-email') {

			$session->remove('info');

			return $this->render('auth/reset_password_send_email.html.twig');

		} else {
			return $this->redirectToRoute('app_index');
		}
	}

	public function resetPasswordConfirmation($token)
	{
		$error_token = '';
		$error = '';
		$success = '';

		$auth = $this->container['authManager'];
		$error_token = $auth->checkResetPasswordToken($token);

		// if token error - no check password
		if ($error_token === null) {

			$request = Request::createFromGlobals();

			if ($request->get('submit_reset_password')) {

				$error = $auth->resetSetNewPassword($request, $token);

				if ($error === null)
					$success = 'Пароль успешно изменен. Вы можете войти используя новый пароль.';
			}
		}		

		return $this->render('auth/reset_password_confirmation.html.twig', [
			'error_token' => $error_token,
			'error' => $error,
			'success' => $success,
			'token' => $token			
		]);
	}	
}