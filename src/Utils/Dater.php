<?php

namespace App\Utils;

use App\Model\User;

class Dater
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function getTimeZone()
	{
		$timezone = Null;

		// get user timezone if isset
		$user = $this->container['userManager']->getUser();
		if (is_object($user) && ($user instanceof User)) {

			$options = unserialize($user->options);
			if (isset($options['timezone'])) {
				$timezone = $options['timezone'];
			}
		}

		if ($timezone == Null) {

			if(isset($_COOKIE['timezone'])) {
				$timezone = (int) $_COOKIE['timezone'];
			}
		}


		return $timezone;
	}

	public function beautiful_date($date) {

		if(!is_object($date)) {

			# if no date format, return row value
			if(!strtotime($date))
				return $date;

			# get datetime object
			$date = new \DateTime($date);
		}

		# russians month name
		$month = ['','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];

		$timezone = $this->getTimeZone();

		$now = new \DateTime();

		if($timezone <= 0)
			$point = "+".abs($timezone)." hours";
		else
			$point = "-".$timezone." hours";

		$date->modify($point);
		$now->modify($point);

			if(strtotime($now->format("d.m.Y")) == strtotime($date->format("d.m.Y")))
				return "Сегодня, в ".$date->format("H:i");
			if(strtotime($now->modify("-1 day")->format("d.m.Y")) == strtotime($date->format("d.m.Y")))
				return "Вчера, в ".$date->format("H:i");
			else {
				$d = $date->format("d");
				$m = $date->format("n");
				$y = $date->format("Y");

				$ny = $now->format("Y");
				if($y == $ny)
					$date = $d." ".$month[$m].", в ".$date->format("H:i");
				else
					$date = $d." ".$month[$m]." ".$y.", в ".$date->format("H:i");
				return $date;
			}
	}

}