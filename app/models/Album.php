<?php

class Album extends \Eloquent {
	protected $fillable = array('name');

	public function works()
	{
		return $this->belongsToMany('work');
	}
}