<?php

class Comment extends \Eloquent {
	protected $fillable = array(
		'content',
		'sender',
		'receiver',
		'isread',
		'work_id',
		'receiver_id'
		);

	protected $hidden = array(
		'created_at',
		'updated_at'
		);

	public function work()
	{
		return $this->belongsTo('Work');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}
}