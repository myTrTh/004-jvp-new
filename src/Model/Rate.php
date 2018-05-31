<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Eloquent
{		
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\Model\User');
	}
}