<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AlbumTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		DB::table('albums')->delete();

		$arrays = array(
				array(
						'name' => '女神'
					),
				array(
						'name' => '旅行'
					),
				array(
						'name' => '美食'
					),
				array(
						'name' => '心情'
					),
				array(
						'name' => '新"图"'
					),
				array(
						'name' => '潮流'
					),
				array(
						'name' => '世界的另一面'
					)
			);

		foreach($arrays as $data)
		{
			Album::create($data);
		}
	}

}