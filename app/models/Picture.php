<?php

class Picture extends \Eloquent {
	protected $fillable = array(
			'url',
			'page',
			'work_id',
			'key',
			'bucket'
		);

	protected $hidden = array(
			'key',
			'bucket',
			'created_at',
			'updated_at',
			'work_id'
		);

	public function work()
	{
		return $this->belongsTo('Work');
	}

	public function sound()
	{
		return $this->hasOne('Sound');
	}

	public function texts()
	{
		return $this->hasMany('Text');
	}

	public function watermark()
	{
		return $this->hasone('Watermark');
	}

	public function bubbles()
	{
		return $this->hasMany('Bubble');
	}
}