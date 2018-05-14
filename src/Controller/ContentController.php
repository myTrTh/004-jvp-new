<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Content;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends Controller
{
	public function list($page)
	{
		$this->container['db'];

		$limit = 10;
		$offset = ($page - 1) * $limit;
		$contents = Content::orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
		$count = Content::orderBy('id', 'desc')->count();

		return $this->render('content/list.html.twig', [
			'contents' => $contents,
			'page' => $page,
			'limit' => $limit,
			'count' => $count
		]);
	}

	public function add()
	{
		if (!$this->container['userManager']->isPermission('content-control-all') && !$this->container['userManager']->isPermission('content-control-own'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$request = Request::createFromGlobals();

		// default values after submit
		$error = '';
		$lastTitle = trim($request->get('title'));
		$lastArticle = trim($request->get('article'));

		if ($request->get('submit_content_add')) {

			$error = $this->container['contentManager']->add($request);

			if ($error === null)
				return $this->redirectToRoute('content_list');
		}

		return $this->render('content/add.html.twig', [
			'error' => $error,
			'lastTitle' => $lastTitle,
			'lastArticle' => $lastArticle
		]);
	}

	public function edit($id)
	{
		$this->container['db'];

		$content = Content::where('id', $id)->first();

		if (!is_object($content) && !($content instanceof Content))
			$error = 'Такого контента не существует';

		if (!$this->container['userManager']->isPermission('content-control-all') && (($this->container['userManager']->isPermission('content-control-own') && $news->user_id == $this->container['userManager']->getUser()['id']) == false))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		// default values after submit
		$error = '';
		$success = '';

		$request = Request::createFromGlobals();

		if ($request->get('submit_content_edit')) {

			$error = $this->container['contentManager']->edit($id, $request);

			if ($error === null)
				return $this->redirectToRoute('content_edit', ['id' => $id]);
		}

		if ($request->get('submit_delete_image')) {

			$error = $this->container['contentManager']->deleteImage($id, $request);

			if ($error === null)
				return $this->redirectToRoute('content_edit', ['id' => $id]);
		}				

		return $this->render('content/edit.html.twig', [
			'error' => $error,
			'content' => $content
		]);
	}

	public function delete($id)
	{
		$this->container['db'];

		// default values after submit
		$error = '';

		$content = Content::where('id', $id)->first();

		if (!is_object($content) && !($content instanceof Content))
			$error = 'Такого контента не существует';

		if (!$this->container['userManager']->isPermission('content-control-all') && (($this->container['userManager']->isPermission('content-control-own') && $news->user_id == $this->container['userManager']->getUser()['id']) == false))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$request = Request::createFromGlobals();

		if ($request->get('submit_content_delete')) {

			$error = $this->container['contentManager']->delete($id, $request);

			if ($error === null)
				return $this->redirectToRoute('content_list');
		}

		return $this->render('content/delete.html.twig', [
			'error' => $error,
			'content' => $content
		]);
	}	
}