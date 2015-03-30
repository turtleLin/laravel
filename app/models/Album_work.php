<?php

class Album_work extends \Eloquent {
    protected $table = 'album_work';
	protected $fillable = ['album_id','work_id'];
	protected $hidden = array('created_at','updated_at');
}