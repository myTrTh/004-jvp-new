<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Tournament;
use App\Model\Upload;

class TournamentController extends Controller
{
	public function list()
	{
		$this->container['db'];

		$tournaments = Tournament::where('status', '!=', '3')->get();

		return $this->render('admin/tournament_list.html.twig', [
			'tournaments' => $tournaments
		]);
	}

	public function show($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$request = Request::createFromGlobals();
		$error = '';

		$tournament = Tournament::where('id', $id)->first();

		if ($request->get('submit_tournament_name')) {
			$error = $this->container['adminTournament']->nameChange($id, $request);

			if ($error === null)
				return $this->redirectToRoute('admin_tournament_show', ['id' => $id]);
		}

		if ($request->get('submit_tournament_archive')) {
			$error = $this->container['adminTournament']->setArchive($id, $request);

			if ($error === null)
				return $this->redirectToRoute('admin_tournaments');
		}

		if ($request->get('submit_tournament_delete')) {
			$error = $this->container['adminTournament']->setDelete($id, $request);

			if ($error === null)
				return $this->redirectToRoute('admin_tournaments');
		}		

		return $this->render('admin/tournament_show.html.twig', [
			'tournament' => $tournament,
			'error' => $error
		]);
	}

	public function logo($id)
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$request = Request::createFromGlobals();
		
		$error = '';

		$tournament = Tournament::where('id', $id)->first();

		if ($request->get('submit_tournament_logo')) {
			$error = $this->container['adminTournament']->setLogo($id, $request);

			if ($error === null)
				return $this->redirectToRoute('admin_tournament_logo', ['id' => $id]);
		}

		$logo = Upload::where('type', 'logo')->orderBy('id', 'desc')->get();

		return $this->render('admin/tournament_logo.html.twig', [
			'tournament' => $tournament,
			'logo' => $logo,
			'error' => $error
		]);		
	}

	public function create()
	{
		if (!$this->container['userManager']->isAccess('ROLE_MODERATOR') && !$this->container['userManager']->isAccess('ROLE_ADMIN') && !$this->container['userManager']->isAccess('ROLE_SUPER_ADMIN'))
			return $this->render('error/page403.html.twig', array('errno' => 403));

		$this->container['db'];

		$request = Request::createFromGlobals();
		$error = '';

		if ($request->get('submit_tournament_create')) {
			$error = $this->container['adminTournament']->create($request);

			if ($error === null)
				return $this->redirectToRoute('admin_tournaments');
		}

		$tournaments = Tournament::all();

		return $this->render('admin/tournament_create.html.twig', [
			'tournaments' => $tournaments,
			'error' => $error
		]);		
	}
}