<?php

class Album_work extends \Eloquent {
    protected $table = 'album_work';
	protected $fillable = ['album_id','work_id'];
    public $timestamps = false;
}