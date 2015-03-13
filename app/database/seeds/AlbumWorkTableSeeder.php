<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AlbumWorkTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 7) as $index)
		{
			Album_work::create([
				'album_id' => $index,
				'work_id' => $index + 1
			]);
		}
	}

}