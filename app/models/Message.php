<?php

class Message extends \Eloquent {
	protected $fillable = array('title','content','sender','receiver','isread','user_id');
	public function user()
	{
		return $this->belongsTo('User');
	}
}