<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class NewsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('newss')->delete();

		foreach(range(1, 10) as $index)
		{
			News::create([
				'content' => 'content',
				'sender_id' => 1,
				'user_id' => 77,
				'code' => 0,
			]);
		}
	}

}