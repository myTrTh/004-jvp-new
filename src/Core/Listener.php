<?php

namespace App\Core;

use App\Model\User;
use App\Model\Activity;
use App\Model\Notification;
use App\Model\Guestbook;
use Symfony\Component\HttpFoundation\Request;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Carbon\Carbon;

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
		$activity->lastPage = $request->getPathInfo();
		$activity->save();
	}

	public function setNotification()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return;

		$options = unserialize($user->options);

        // GUESTBOOK NOTIFICATION
		if ($options['notification']['guestbook']) {

			$request = Request::createFromGlobals();

			if (preg_match('/\/guestbook$/', $request->getPathInfo())) {

				$guestbook = Notification::where('user_id', $user->id)->where('route', 'guestbook')->first();
				if (!is_object($guestbook) && !($guestbook instanceof Notification)) {
					$guestbook = new Notification();
					$guestbook->user_id = $user->id;
					$guestbook->route = 'guestbook';
				}

					// update time
					$guestbook->touch();
			}
		}
	}

	public function notification()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return;

		$options = unserialize($user->options);

        // GUESTBOOK NOTIFICATION
		if ($options['notification']['guestbook']) {

			$request = Request::createFromGlobals();

			$guestbook = Notification::where('user_id', $user->id)->where('route', 'guestbook')->first();

			if (!is_object($guestbook) && !($guestbook instanceof Notification))
				$guestbook = new Notification();

			if ($guestbook->updated_at == null)
				$guestbook->updated_at = new \DateTime();
			$messages_new = Guestbook::where('created_at', '>', $guestbook->updated_at)->where('user_id', '!=', $user->id)->count();

			return ['guestbook' => $messages_new];
		}
	}


  //       $guestbook_last_date = Notification::where('id', $user->id)->where('route', 'guestbook')->latest()->first();
  //       if ($guestbook_last_date) {
			
		// 	$new_messages = Guestbook::where('created_at', '>', $guestbook_last_date->created_at)->count();
		// 	echo $new_messages;

  //       }


  //       $guestbook_notification = Notification::where('id', $user_id)->where('route', 'guestbook')->first();
		// if (!is_object($guestbook_notification) && !($guestbook_notification instanceof Notification))
		// 	$guestbook_notification = new Notification();

		// $guestbook_notification->user_id = $user->id;
		// $guestbook_notification->route = "guestbook";
		// $guestbook_notification->save();

        // $new_guestbook = $this->em->getRepository('AppUserBundle:Notification')->get_single_new($userId, 'AppGuestbookBundle:Guestbook', $guestbook_last_date);

        // if($options['notification']['notification_guestbook'] == 'true')
        //     $this->twig->addGlobal('notification_guestbook', $new_guestbook);
}