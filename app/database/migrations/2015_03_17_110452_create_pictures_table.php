<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pictures', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('url');
			$table->string('key');
			$table->integer('page');
			$table->string('bucket')->default('rabbitpremobile');
			$table->integer('work_id')->unsigned()->index('work_id');
			$table->timestamps();

			$table
				->foreign('work_id')
				->references('id')->on('works')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table
				->unique(array('work_id','page'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pictures');
	}

}
