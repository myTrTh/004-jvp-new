<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cup extends Eloquent
{
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\Model\User', 'user_id', 'id');
	}	

	public function image()
	{
		return $this->belongsTo('App\Model\Upload', 'image_id', 'id');
	}		
}