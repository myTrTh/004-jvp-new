<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Model\Adminbook;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminbookController extends Controller
{
	public function guestbook($page)
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$limit = 20;
		$offset = ($page - 1) * $limit;
		$guestbook = Adminbook::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
		$count = Adminbook::orderBy('id', 'desc')->count();

		$request = Request::createFromGlobals();
		$lastGuestbook = trim($request->get('message'));
		$error = '';

		if ($request->get('submit_guestbook')) {
			// if no auth - redirect to login page

			if (!$this->container['userManager']->isAccess('ROLE_USER'))
				return $this->render('auth/login.html.twig');

			if (!$this->container['userManager']->isPermission('guestbook-write'))
				return "Вам запрещено писать сообщения.";

			$error = $this->container['guestbookManager']->add($request, 'adminbook');

			if ($error === null)
				return $this->redirectToRoute('admin_guestbook');
		}

		return $this->render('admin/guestbook.html.twig', [
			'guestbook' => $guestbook,
			'page' => $page,
			'limit' => $limit,
			'count' => $count,
			'error' => $error,
			'lastGuestbook' => $lastGuestbook
		]);
	}
}