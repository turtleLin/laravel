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
			$table->string('sender');
			$table->string('receiver');
			$table->boolean('isread')->default(0);
			$table->integer('work_id')->unsigned()->index('work_id');
			$table->integer('receiver_id')->unsigned()->index('receiver_id');
			$table->timestamps();

			$table                         
                ->foreign('work_id')
                ->references('id')->on('works') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                         
                ->foreign('receiver_id')
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
