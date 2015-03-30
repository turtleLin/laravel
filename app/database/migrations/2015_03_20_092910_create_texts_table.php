<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('texts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('text');
			$table->string('x');
			$table->string('y');
			$table->string('w');
			$table->string('h');
			$table->string('color');
			$table->string('font');
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
		Schema::drop('texts');
	}

}
