<?php

namespace App\Core;

use App\Model\User;
use App\Model\Activity;
use Symfony\Component\HttpFoundation\Request;

class Listener
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function activity()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return;

		$activity = Activity::where('user_id', $user->id)->first();
		if (!is_object($activity) && !($activity instanceof Activity))
			$activity = new Activity();

		$request = Request::createFromGlobals();
		$activity->user_id = $user->id;
		$activity->ip = $request->getClientIp();
		$activity->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$activity->lastPage = $request->getUri();
		$activity->save();
	}
}