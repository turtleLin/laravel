<?php

class News extends \Eloquent {
	protected $table = 'newss';
	protected $fillable = array(
			'content',
			'work_id',
			'sender_id',
			'user_id',
			'code',
			'isread'
		);

	protected $hidden = array(
			'updated_at'
		);

	public function user()
	{
		return $this->belongsTo('User');
	}
}