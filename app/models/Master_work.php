<?php

class Master_work extends \Eloquent {
	protected $fillable = array('master_id','user_id');
	protected $hidden = array('created_at','updated_at');
}