<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Tournament;
use App\Model\Upload;

class AdminTournament extends Manager
{
	public function create($request)
	{		
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// prepare data
		$name = trim($request->get('tournament_name'));

		if ($error = $this->ifEmptyStringValidate($name, 'Название'))
			return $error;

		if ($error = $this->ifDublicate($name))
			return $error;

		// Tournament status
		// 1 tournament request
		// 2 tournament active
		// 3 tournament archive
		$tournament = new Tournament();
		$tournament->title = $name;
		$tournament->user_id = $user->id;
		$tournament->status = 1;
		$tournament->save();

		return;
	}

	public function nameChange($id, $request)
	{		
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;

		// prepare data
		$name = trim($request->get('tournament_name'));

		if ($error = $this->ifEmptyStringValidate($name, 'Название'))
			return $error;

		if ($error = $this->ifDublicate($name))
			return $error;

		$tournament = Tournament::where('id', $id)->first();
		if (!is_object($tournament) && !($tournament instanceof Tournament))
			return "Турнир не найден";

		$tournament->title = $name;
		$tournament->save();

		return;
	}

	public function setArchive($id, $request)
	{
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;
		
		$tournament = Tournament::where('id', $id)->first();
		if (!is_object($tournament) && !($tournament instanceof Tournament))
			return "Турнир не найден";

		$tournament->status = 3;
		$tournament->save();

	}

	public function setDelete($id, $request)
	{
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;
		
		$tournament = Tournament::where('id', $id)->first();
		if (!is_object($tournament) && !($tournament instanceof Tournament))
			return "Турнир не найден";

		$tournament->delete();

	}

	public function setLogo($id, $request)
	{
		$this->container['db'];

		if ($error = $this->container['tokenManager']->checkCSRFtoken($request->get('_csrf_token')))
			return $error;
		
		$tournament = Tournament::where('id', $id)->first();
		if (!is_object($tournament) && !($tournament instanceof Tournament))
			return "Турнир не найден";

		$image = trim($request->get('image'));

		$upload = Upload::where('id', $image)->where('type', 'logo')->first();

		if (!is_object($upload) && !($upload instanceof Upload))
			return "Изображение не найдено";

		$tournament->image = $upload->image;
		$tournament->save();
	}
}