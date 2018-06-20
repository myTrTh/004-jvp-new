<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Tournament;

class TournamentController extends Controller
{
	public function list($page)
	{
		$this->container['db'];
		$limit = 10;
		$offset = ($page - 1) * $limit;
		$tournaments = Tournament::where('status', '!=', 3)->orderBy('created_at', 'asc')->offset($offset)->limit($limit)->get();
		$count = Tournament::count();

		return $this->render('tournament/list.html.twig', [
			'tournaments' => $tournaments,
			'page' => $page,
			'limit' => $limit,
			'count' => $count
		]);
	}

	public function show($id)
	{
		$this->container['db'];
		
		$tournament = Tournament::where('id', $id)->where('status', '!=', 3)->first();

		if (!is_object($tournament) && !($tournament instanceOf Tournament))
			return $this->render('error/page404.html.twig', [
				'error' => 'Турнир не найден',
			]);

		return $this->render('tournament/show.html.twig', [
			'tournament' => $tournament,
		]);		
	}
}