<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comment_user', function(Blueprint $table)
		{
			$table->integer('comment_id')->unsigned()->index('comment_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->boolean('isread')->default(0);
			$table->timestamps();

			$table                         
                ->foreign('user_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                         
                ->foreign('comment_id')
                ->references('id')->on('comments') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
            	->primary(array('user_id','comment_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comment_user');
	}

}
