<?php

class Album extends \Eloquent {
	protected $fillable = array('name');
	protected $hidden = array('created_at','updated_at');
	public function works()
	{
		return $this->belongsToMany('Work');
	}
}