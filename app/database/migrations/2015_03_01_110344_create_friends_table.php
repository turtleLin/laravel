<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friends', function(Blueprint $table)
		{
			$table
            	->integer("user_id")		 	// 用户id
            	->unsigned()   
            	->index("user_id");

            $table
            	->integer("friend_id")		 	// 好友id
            	->unsigned()   
            	->index("friend_id");

            $table                          // 为cv_id建立外键
                ->foreign('user_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                          // 为cv_id建立外键
                ->foreign('friend_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
            	->primary(array('user_id','friend_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('friends');
	}

}
