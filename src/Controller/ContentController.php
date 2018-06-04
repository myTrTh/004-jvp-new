<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Content;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ContentController extends Controller
{
	public function show($type)
	{
		$this->container['db'];

		$content = Content::where('type', $type)->first();

		return $this->render('content/show.html.twig', [
			'type' => $type,
			'content' => $content
		]);
	}

	public function edit($type)
	{
		$this->container['db'];

		$content = Content::where('type', $type)->first();

		$types = Content::all();

		if (!$this->container['userManager']->isPermission('content-control-all') && !$this->container['userManager']->isPermission('content-control-own'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		// default values after submit
		$error = '';

		$request = Request::createFromGlobals();

		if ($request->get('submit_content_edit')) {

			$error = $this->container['contentManager']->edit($type, $request);

			if ($error === null)
				return $this->redirectToRoute('content_edit', ['type' => $type]);
		}

		return $this->render('admin/content_edit.html.twig', [
			'all_types' => $types,
			'type' => $type,
			'error' => $error,
			'content' => $content
		]);
	}
}