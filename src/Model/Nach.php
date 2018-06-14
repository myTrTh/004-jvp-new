<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Nach extends Eloquent
{
	protected $table = 'nach';

	public function user()
	{
		return $this->belongsTo('App\Model\User', 'user_id', 'id');
	}

	public function message()
	{
		return $this->belongsTo('App\Model\Guestbook', 'message_id', 'id');
	}
}