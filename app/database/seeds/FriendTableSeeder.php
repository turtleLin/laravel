<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class FriendTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		DB::table('friends')->delete();

		$arrays = array(
			array(
				"user_id" => 1,
				"friend_id" => 2
			),
			array(
				"user_id" => 4,
				"friend_id" => 3
			),
			array(
				"user_id" => 4,
				"friend_id" => 1
			),
			array(
				"user_id" => 2,
				"friend_id" => 5
			),
			array(
				"user_id" => 5,
				"friend_id" => 3
			)
		);

		foreach($arrays as $data)
		{
			Friend::create($data);
		}
	}

}