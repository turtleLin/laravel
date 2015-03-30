<?php

class Sound extends \Eloquent {
	protected $fillable = array(
			'url',
			'picture_id',
			'key',
			'bucket'
		);

	protected $hidden = array(
			'id',
			'key',
			'bucket',
			'created_at',
			'updated_at',
			'picture_id'
		);

	public function picture()
	{
		return $this->belongsTo('Picture');
	}
}