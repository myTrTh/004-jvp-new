<?php

namespace App\Core;

use App\Model\User;
use App\Model\Activity;
use App\Model\Notification;
use App\Model\Guestbook;
use App\Model\VoteHead;
use App\Model\VoteUser;
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
		$activity->userAgent = $request->headers->get('User-Agent');
		$activity->lastPage = $request->getPathInfo();
		$activity->save();
	}

	public function setNotification()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return;

		$options = unserialize($user->options);
		$request = Request::createFromGlobals();

        // GUESTBOOK NOTIFICATION
        // for all users, need for define edit message
		// if ($options['notification']['guestbook']) {

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
		// }

        // VOTES NOTIFICATION
        if($options['notification']['vote']) {
            // $this->twig->addGlobal('notification_vote', $new_votes);

	        if (preg_match("/vote\/[0-9]+/", $request->getPathInfo(), $vote)) {
	        	$vote_page = Notification::where('user_id', $user->id)->where('route', $vote[0])->first();
	        	if (!is_object($vote_page) && !($vote_page instanceof Notification)) {
	        		$vote_page = new Notification();
	        		$vote_page->user_id = $user->id;
	        		$vote_page->route = $vote[0];
	        		$vote_page->save();
	        	}
	        }
    	}
	}

	public function notification()
	{
		$user = $this->container['userManager']->getUser();
		if (!is_object($user) && !($user instanceof User))
			return;

		$options = unserialize($user->options);
		$request = Request::createFromGlobals();
		
		$notification = [];

        // GUESTBOOK NOTIFICATION
		if ($options['notification']['guestbook']) {

			$guestbook = Notification::where('user_id', $user->id)->where('route', 'guestbook')->first();

			if (!is_object($guestbook) && !($guestbook instanceof Notification))
				$guestbook = new Notification();

			if ($guestbook->updated_at == null)
				$guestbook->updated_at = new \DateTime();
			$messages_new = Guestbook::where('created_at', '>', $guestbook->updated_at)->where('user_id', '!=', $user->id)->count();

			$notification['guestbook'] = $messages_new;
		}

        // VOTES NOTIFICATION
		if ($options['notification']['vote']) {

			$all_votes = VoteHead::where('created_at', '>', Carbon::now()->subDays(3))->pluck('id')->toArray();

			if (count($all_votes) > 0) {

				// set votes
				$set_votes = VoteUser::where('user_id', $user->id)->whereIn('vote_head_id', $all_votes)->pluck('vote_head_id')->toArray();

				$votes_id = [];
				// visit votes
				$visit_votes = Notification::where('user_id', $user->id)->where('created_at', '>', Carbon::now()->subDays(3))->where('route', 'like', 'vote/%')->get();
				foreach ($visit_votes as $vote) {
					$votes_id[] = substr($vote->route, 5);
				}

				$votes_id = array_unique($votes_id);

				$votes = count(array_diff($all_votes, $set_votes, $votes_id));

			} else {
				$votes = 0;
			}

			$notification['vote'] = $votes;
		}

		return $notification;
	}
}