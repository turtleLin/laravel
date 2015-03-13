<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class MessageTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		DB::table('messages')->delete();

		foreach(range(1, 10) as $index)
		{
			Message::create([
				'title' => 'title_' . $index,
				'content' => 'content_' . $index,
				'sender' => 'admin',
				'receiver' => 'user_' . $index,
				'user_id' => $index
			]);
		}
	}

}