<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Guestbook;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestbookController extends Controller
{
	public function guestbook($page)
	{
		$this->container['db'];

		$limit = 10;
		$offset = ($page - 1) * $limit;
		$guestbook = Guestbook::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
		$count = Guestbook::orderBy('id', 'desc')->count();

		$request = Request::createFromGlobals();
		$lastGuestbook = trim($request->get('message'));
		$error = '';

		if ($request->get('submit_guestbook')) {
			// if no auth - redirect to login page
			if (!$this->container['userManager']->isAccess('ROLE_USER'))
				return $this->render('auth/login.html.twig');

			$error = $this->container['guestbookManager']->add($request);

			if ($error === null)
				return $this->redirectToRoute('guestbook');
		}

		return $this->render('guestbook/guestbook.html.twig', [
			'guestbook' => $guestbook,
			'page' => $page,
			'limit' => $limit,
			'count' => $count,
			'error' => $error,
			'lastGuestbook' => $lastGuestbook
		]);
	}

	public function ajax_rate()
	{
		$this->container['db'];

		// // if no user
		$author = $this->container['userManager']->getUser();
		if (!is_object($author) && !($author instanceof User)) {
			$response = [
				'error' => 1,
				'error-message' => 'Вы не зарегистрированы.'
			];
			return new Response(json_encode($response));
		}

		if ($this->container['userManager']->isPermission('rate-action') === false)
			return 'Вам запрещенно оценивать сообщения';

		$request = Request::createFromGlobals();
		$id = $request->get('id');
		$sign = $request->get('sign');

		if ($sign !== 'd' or $sign !== 'u') {
			$response = [
				'error' => 1,
				'error-message' => 'Ошибка при голосовании.'
			];
			return new Response(json_encode($response));
		}

		if ($sign === 'u')
			$s = 1;
		else if ($sign === 'd')
			$s = -1;

		$message = Guestbook::where('id', $id)->first();
		if (!is_object($message) && !($message instanceof Guestbook)) {
			$response = [
				'error' => 1,
				'error-message' => 'Ошибка при голосовании.'
			];
			return new Response(json_encode($response));
		}

		$rate = new Rate();
		$rate->message_id = $message->id;
		$rate->author_id = $author->id;
		$rate->user_id = $message->user_id;
		$rate->sign = $s;
		$rate->save();

		return new Response(json_encode(1));
	}
}