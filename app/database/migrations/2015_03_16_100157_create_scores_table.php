<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('work_id')->unsigned()->index('work_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('score');
			$table->timestamps();

			$table                         
                ->foreign('user_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                         
                ->foreign('work_id')
                ->references('id')->on('works') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
            	->unique(array('user_id','work_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('scores');
	}

}
