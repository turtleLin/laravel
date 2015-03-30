<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterWorkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('master_work', function(Blueprint $table)
		{
			$table
            	->integer("master_id")		 	
            	->unsigned()   
            	->index("master_id");

            $table
            	->integer("user_id")		 	
            	->unsigned()   
            	->index("user_id");

        	$table->timestamps();

            $table                          
                ->foreign('master_id')
                ->references('id')->on('masters') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table                          
                ->foreign('user_id')
                ->references('id')->on('users') 
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
            	->primary(array('master_id','user_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('master_work');
	}

}
