<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class CommentTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Comment::create([
				'content' => 'content',
				'sender' => 'user_' . ($index + 1) % 10,
				'receiver' => 'user_' . $index,
				'work_id' => $index,
				'receiver_id' => $index
			]);
		}
	}

}