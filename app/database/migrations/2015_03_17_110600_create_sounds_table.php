<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoundsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sounds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('url');
			$table->string('key');
			$table->string('bucket')->default('rabbitpremobile');
			$table->integer('picture_id')->unsigned()->index('picture_id');
			$table->timestamps();

			$table
				->foreign('picture_id')
				->references('id')->on('pictures')
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
		Schema::drop('sounds');
	}

}
