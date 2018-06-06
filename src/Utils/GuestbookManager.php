<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Guestbook;
use App\Model\Adminbook;
use App\Model\User;
use App\Model\Rate;

class GuestbookManager extends Manager
{
	public function add($request, $book = 'guestbook')
	{
		// prepare data
		$message = trim($request->get('message'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->ifEmptyStringValidate($message, 'Сообщение'))
			return $error;

		if ($book == 'guestbook') {
			if ($error = $this->duplicate($message))
				return $error;
		} else if ($book == 'adminbook') {
			if ($error = $this->adminDuplicate($message))
				return $error;			
		}

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceOf User))
			return 'Вы не авторизированы.';

		if ($this->container['userManager']->isPermission('guestbook-write') === false)
			return 'Вам запрещенно писать сообщения';

		if ($book == 'guestbook')
			$guestbook = new Guestbook();
		else if ($book == 'adminbook')
			$guestbook = new Adminbook();
		$guestbook->user_id = $user->id;		
		$guestbook->message = $message;
		$guestbook->save();

		return;
	}

	public function rate($request)
	{
		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('csrf_token'))) {
			return array (
				'error' => 1,
				'error_message' => $error
			);
		}
			

		// // if no user
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User)) {
			return array (
				'error' => 1,
				'error_message' => 'Вы не зарегистрированы.'
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
				'error_message' => 'Возникла ошибка при голосовании.'
			);
		}

		$message = Guestbook::where('id', $id)->first();

		if (!is_object($message) && !($message instanceof Guestbook)) {
			return array (
				'error' => 1,
				'error_message' => 'Возникла ошибка при голосовании.'
			);
		}

		// запрет оценивать сообщения повторно
		if ($user->id == $message->user_id) {
			return array (
				'error' => 1,
				'error_message' => 'Нельзя оценивать свои сообщения.'
			);
		}

		$order = Rate::where('message_id', $message->id)->where('user_id', $user->id)->first();
		if ($order) {
			return array (
				'error' => 1,
				'error_message' => 'Запрещено повторно голосовать за сообщение.'
			);
		}

		$rate = new Rate();
		$rate->message_id = $message->id;
		$rate->author_id = $message->user_id;
		$rate->user_id = $user->id;
		$rate->sign = $s;
		$rate->save();

		$message_sum_rates = $message->rates->sum('sign');
		$author_sum_rates = $message->author->rates->sum('sign');

		$plus = '';
		$minus = '';
		foreach ($message->rates as $rate) {
			if ($rate->sign == 1)
				$plus .= $rate->user->username.', ';
			else
				$minus .= $rate->user->username.', ';
		}

		$plus = substr($plus, 0, -2);
		$minus = substr($minus, 0, -2);

		return array (
			'error' => 0,
			'message_sum_rates' => $message_sum_rates,
			'user' => $message->user_id,
			'author_sum_rates' => $author_sum_rates,
			'plus_users' => $plus,
			'minus_users' => $minus
		);
	}
}