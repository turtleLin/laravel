<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('content');
			$table->integer('sender_id')->unsigned()->index('sender_id');
			$table->integer('work_id')->unsigned()->index('work_id');
			$table->timestamps();

			$table                         
                ->foreign('work_id')
                ->references('id')->on('works') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                         
                ->foreign('sender_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
