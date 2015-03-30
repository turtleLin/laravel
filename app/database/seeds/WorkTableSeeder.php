<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class WorkTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 50) as $index)
		{
			Work::create([
				'title' => 'title_' . $index,
				'description' => 'description_' . $index,
				'user_id' => $index
			]);
		}
	}

}