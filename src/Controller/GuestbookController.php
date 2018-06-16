<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Guestbook;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class GuestbookController extends Controller
{
	public function guestbook($page)
	{
		$this->container['db'];

		$limit = 20;
		$offset = ($page - 1) * $limit;
		$guestbook = Guestbook::withTrashed()->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
		$count = Guestbook::withTrashed()->count();

		$request = Request::createFromGlobals();
		$lastGuestbook = trim($request->get('message'));
		$error = '';

		if ($request->get('submit_guestbook')) {
			// if no auth - redirect to login page

			if (!$this->container['userManager']->isAccess('ROLE_USER'))
				return $this->render('auth/login.html.twig');

			if (!$this->container['userManager']->isPermission('guestbook-write'))
				return "Вам запрещено писать сообщения.";

			$error = $this->container['guestbookManager']->add($request);

			$session = new Session();
			if ($session->get('edit') !== null && $error === null) {
				$edit = $session->get('edit');
				$session->remove('edit');
				return $this->redirectToRoute('guestbook_post', ['post_id' => $edit]);

			}

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

	public function post($post_id)
	{
		$this->container['db'];

		$limit = 20;
		$count = ceil(Guestbook::withTrashed()->count() / $limit);
		$number_page = floor($post_id/$limit);
		$page = $count - $number_page;

        return $this->redirectToRoute('guestbook', ['page' => $page, '_fragment' => 'post'.$post_id]);
	}
}