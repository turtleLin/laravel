<?php

class Token extends \Eloquent {
	protected $fillable = ['tokens','user_id'];

	public function user()
	{
		return $this->belongsTo('User');
	}
}