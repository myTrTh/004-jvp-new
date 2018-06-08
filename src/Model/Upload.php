<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Eloquent
{
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $table = 'upload';	
}