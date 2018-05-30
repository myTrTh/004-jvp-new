<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Guestbook;
use App\Model\User;
use App\Model\Rate;

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

	public function rate($request)
	{
		// // if no user
		$author = $this->container['userManager']->getUser();
		if (!is_object($author) && !($author instanceof User)) {
			return array (
				'error' => 1,
				'error-message' => 'Вы не зарегистрированы.'
			);
		}

		if (!$this->container['userManager']->isPermission('rate-action')) {
			return array (
				'error' => 1,
				'error_message' => 'Вам запрещено оценивать сообщения.'
			);
		}

		// prepare data
		$id = $request->get('id');
		$sign = $request->get('sign');

		if ($sign == "d" || $sign == "u") {
			if ($sign === 'u')
				$s = 1;
			else if ($sign === 'd')
				$s = -1;
		} else {
			return array (
				'error' => 1,
				'error_message' => 'Возникла ошибка при голосовании1.'
			);
		}

		$message = Guestbook::where('id', $id)->first();

		if (!is_object($message) && !($message instanceof Guestbook)) {
			return array (
				'error' => 1,
				'error_message' => 'Возникла ошибка при голосовании.'
			);
		}

		$rate = new Rate();
		$rate->message_id = $message->id;
		$rate->author_id = $author->id;
		$rate->user_id = $message->user_id;
		$rate->sign = $s;
		$rate->save();

		$message_rates = $message->rates->sum('sign');
		$message_user = $message->author->rates->sum('sign');

		return array (
			'error' => 0,
			'message_rates' => $message_rates,
			'user' => $message->user_id,
			'message_user' => $message_user
		);
	}
}