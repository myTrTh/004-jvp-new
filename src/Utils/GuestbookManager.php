<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Guestbook;

class GuestbookManager extends Manager
{
	public function add($request)
	{
		// prepare data
		$message = trim($request->get('message'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->ifEmptyStringValidate($message))
			return $error;

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceOf User))
			return 'Вы не авторизированы.';

		if ($this->container['userManager']->isPermission('guestbook-write') === false)
			return 'Вам запрещенно писать сообщения';

		$guestbook = new Guestbook();
		$guestbook->user_id = $user->id;		
		$guestbook->message = $message;
		$guestbook->save();

		return;
	}
}