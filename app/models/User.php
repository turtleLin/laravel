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
		"isadmin"
		);

	public function messages()
	{
		return $this->hasMany('Message');
	}

	public function comments()
	{
		return $this->hasMany('Comment','receiver_id');
	}

	public function friends()
	{
		return $this->belongsToMany('User','friends','user_id','friend_id');
	}

	public function works()
	{
		return $this->hasMany('Work');
	}

	public function likes()
	{
		return $this->belongsToMany('Work','likes','user_id','work_id');
	}
}
