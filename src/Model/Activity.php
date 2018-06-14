<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Eloquent
{
	public function user()
	{
		return $this->belongsTo('App\Model\User', 'user_id', 'id');
	}
}