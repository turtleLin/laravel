<?php

class Token extends \Eloquent {
	protected $fillable = ['token','user_id'];

	public function user()
	{
		return $this->belongsTo('User');
	}
}