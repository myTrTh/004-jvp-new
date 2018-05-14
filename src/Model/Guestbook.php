<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guestbook extends Eloquent
{
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $table = 'guestbook';

	public function author()
	{
		return $this->belongsTo('App\Model\User', 'user_id', 'id');
	}	
}