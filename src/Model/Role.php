<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent
{		
	public function users()
	{
		return $this->belongsToMany('App\Model\User');
	}

	public function permissions()
	{
		return $this->belongsToMany('App\Model\Permission');
	}	
}