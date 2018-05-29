<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Rate extends Eloquent
{		
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function users()
	{
		return $this->belongsToMany('App\Model\User');
	}
}