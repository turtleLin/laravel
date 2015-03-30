<?php

class Work extends \Eloquent {
	protected $fillable = array('title','description','user_id','hot','scorenum');
	protected $hidden = array('updated_at');

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function resources()
	{
		return $this->hasMany('Resource');
	}

	public function pictures()
	{
		return $this->hasMany('Picture');
	}

	public function sounds()
	{
		return $this->hasManyThrough('Sound','Picture');
	}
	//返回点赞用户
	public function likes()
	{
		return $this->belongsToMany('User','likes','work_id','user_id');
	}

	public function comments()
	{
		return $this->hasMany('Comment');
	}

	public function albums()
	{
		return $this->belongsToMany('Album');
	}

	public function scores()
	{
		return $this->hasMany('Score');
	}
	//返回likes列表
	public function like()
	{
		return $this->hasMany('Like');
	}
}