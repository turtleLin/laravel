<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewssTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newss', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('content');
			$table->integer('work_id')->nullable();
			$table->integer('sender_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->boolean('code');
			$table->boolean('isread')->default(0);
			$table->timestamps();

			$table                         
                ->foreign('user_id')
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
		Schema::drop('newss');
	}

}
