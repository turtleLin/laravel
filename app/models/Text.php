<?php

class Text extends \Eloquent {
	protected $fillable = array(
				'text',
				'x',
				'y',
				'w',
				'h',
				'color',
				'font',
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