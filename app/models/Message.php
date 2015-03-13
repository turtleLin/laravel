<?php

class Message extends \Eloquent {
	protected $fillable = array(
		'title',
		'content',
		'sender',
		'receiver',
		'isread',
		'user_id'
		);

	protected $hidden = array(
		'created_at',
		'updated_at'
		);
	
	public function user()
	{
		return $this->belongsTo('User');
	}
}