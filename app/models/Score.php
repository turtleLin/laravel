<?php

class Score extends \Eloquent {
	protected $fillable = array(
				'score',
				'work_id',
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

	public function work()
	{
		return $this->belongsTo('Work');
	}
}