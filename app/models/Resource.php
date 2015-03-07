<?php

class Resource extends \Eloquent {
	protected $fillable = array('work_id','page','key','bucket');

	public function work()
	{
		return $this->belongsTo('Work');
	}
}