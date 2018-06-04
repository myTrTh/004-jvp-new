<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Content;

class ContentManager extends Manager
{
	public function edit($type, $request)
	{
		// prepare data
		$article = trim($request->get('article'));

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		if ($error = $this->ifEmptyStringValidate($article, 'Текст'))
			return $error;

		$content = Content::where('type', $type)->first();

		if (!is_object($content) && !($content instanceof Content))
			return 'Такого контента не существует';

		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return 'Вы не авторизированы';

		$content->article = $article;
		$content->user_id = $user->id;
		$content->save();

		return;
	}
}