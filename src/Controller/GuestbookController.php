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
		$request = Request::createFromGlobals();
		$result = $this->container['guestbookManager']->rate($request);

		return new Response(json_encode($result));
	}
}