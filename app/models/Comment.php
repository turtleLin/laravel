<?php

class Comment extends \Eloquent {
	protected $fillable = array(
		'content',
		'sender_id',
		'work_id',
		'id'
		);

	protected $hidden = array(
		'updated_at'
		);

	public function work()
	{
		return $this->belongsTo('Work');
	}

	public function user()
	{
		return $this->belongsTo('User','sender_id');
	}

	public function users()
	{
		return $this->belongsToMany('User');
	}
}