<?php

class Master extends \Eloquent {
	protected $fillable = array('name','color');
	protected $hidden = array('created_at','updated_at');
	public function users()
	{
		return $this->belongsToMany('User');
	}
}