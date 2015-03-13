<?php

class Resource extends \Eloquent {
	protected $fillable = array(
		'work_id',
		'page',
		'key',
		'bucket',
		'downurl'
		);
	protected $hidden = array(
		'create_at',
		'id',
		'updated_at',
		'work_id',
		'bucket',
		'key'
		);

	public function work()
	{
		return $this->belongsTo('Work');
	}
}