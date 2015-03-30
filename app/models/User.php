<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array(
		'password', 
		'remember_token',
		'permissions',
		'activated_at',
		'isadmin',
		'activation_code',
		'last_login',
		'persist_code',
		'reset_password_code',
		'created_at',
		'updated_at',
		'pivot',
		'activated'
		);

	protected $fillable = array(
		'username', 
		'email',
		'password',
		"gender",
		"isadmin",
		'head',
		'hot'
		);

	public function messages()
	{
		return $this->hasMany('Message');
	}

	public function friends()
	{
		return $this->belongsToMany('User','friends','user_id','friend_id');
	}

	public function fans()
	{
		return $this->belongsToMany('User','friends','friend_id','user_id');
	}

	public function works()
	{
		return $this->hasMany('Work');
	}

	public function likes()
	{
		return $this->belongsToMany('Work','likes','user_id','work_id');
	}

	public function resources()
	{
		return $this->hasManyThrough('Resource','Work');
	}

	public function tokens()
	{
		return $this->hasMany('Token');
	}

	public function comments()
	{
		return $this->belongsToMany('Comment');
	}

	public function newss()
	{
		return $this->hasMany('News');
	}

	public function masters()
	{
		return $this->belongsToMany('Master');
	}

}
