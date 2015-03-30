<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class MasterTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		DB::table('masters')->delete();

		$arrays = array(
				array(
						'name' => '女神范',
						'color' => '214000000'
					),
				array(
						'name' => '旅行家',
						'color' => '000152000'
					),
				array(
						'name' => '文艺派',
						'color' => '194130129'
					),
				array(
						'name' => '潮流咖',
						'color' => '160058213'
					),
				array(
						'name' => '摄影师',
						'color' => '000166192'
					)				
			);

		foreach($arrays as $array)
		{
			
			Master::create($array);
			
		}
	}

}