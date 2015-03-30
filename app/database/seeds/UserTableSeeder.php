<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		DB::table('users')->delete();

		foreach(range(1, 50) as $index)
		{
			User::create(
				array(
					"username" => "user_" . $index,
                	"password" => Hash::make("zhaojian"),
                	"email" => "user_" . $index . "@qq.com",
                	"gender" => "male",
                	'activated' => 1
	            )
			);
		}
	}

}