<?php

class Bubble extends \Eloquent {
	protected $fillable = array(
				'type',
				'x',
				'y',
				'w',
				'h',
				'color',
				'alpha',
				'picture_id'
		);

	protected $hidden = array(
				'created_at',
				'updated_at',
				'picture_id'
		);

	public function picture()
	{
		return $this->belongsTo('Picture');
	}
}