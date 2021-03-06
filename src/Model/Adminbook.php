<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adminbook extends Eloquent
{
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $table = 'adminbook';

	public function author()
	{
		return $this->belongsTo('App\Model\User', 'user_id', 'id');
	}

	public function rates()
	{
		return $this->hasMany('App\Model\Rate', 'message_id', 'id');
	}
}