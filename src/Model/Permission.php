<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Permission extends Eloquent
{		
	public function users()
	{
		return $this->belongsToMany('App\Model\User');
	}

	public function roles()
	{
		return $this->belongsToMany('App\Model\Role');
	}	
}