<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Eloquent
{
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $dates = ['deleted_at'];
		
	public function roles()
	{
		return $this->belongsToMany('App\Model\Role');
	}

	public function permissions()
	{
		return $this->belongsToMany('App\Model\Permission');
	}

	public function messages()
	{
		return $this->hasMany('App\Model\Guestbook', 'user_id', 'id');
	}

	public function rates()
	{
		return $this->hasMany('App\Model\Rate', 'author_id', 'id');
	}

	public function achives()
	{
		return $this->hasMany('App\Model\Achive', 'user_id', 'id');
	}

	public function cups()
	{
		return $this->hasMany('App\Model\Cup', 'user_id', 'id');
	}

	public function activity()
	{
		return $this->belongsTo('App\Model\Activity', 'id', 'user_id');
	}

	public function nach($user_id)
	{
		return Guestbook::where('user_id', $user_id)->where('id', 'like', '%00')->pluck('id')->toArray();
	}

	public function supernach()
	{
		return $this->hasMany('App\Model\Nach', 'user_id', 'id');
	}
}