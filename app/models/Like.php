<?php

class Like extends \Eloquent {
	protected $table = 'likes';
	protected $fillable = array('work_id','user_id');
	
}