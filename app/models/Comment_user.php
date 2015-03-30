<?php

class Comment_user extends \Eloquent {
	protected $table = 'comment_user';
	protected $fillable = ['comment_id','user_id','isread'];
	protected $hidden = array('created_at','updated_at');
}