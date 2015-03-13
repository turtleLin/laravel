<?php

class Work extends \Eloquent {
	protected $fillable = array('name','user_id');

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function resources()
	{
		return $this->hasMany('Resource');
	}

	public function likes()
	{
		return $this->belongsToMany('User','likes','work_id','user_id');
	}

	public function comments()
	{
		return $this->hasMany('Comment');
	}

	public function albums()
	{
		return $this->belongsToMany('albums');
	}
}