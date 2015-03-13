<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class WorkTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Work::create([
				'name' => 'work_' . $index,
				'user_id' => $index
			]);
		}
	}

}