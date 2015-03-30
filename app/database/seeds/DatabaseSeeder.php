<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
		$this->call('FriendTableSeeder');
		$this->call('WorkTableSeeder');
		$this->call('AlbumTableSeeder');
		$this->call('MasterTableSeeder');
		// $this->call('AlbumWorkTableSeeder');
		// $this->call('CommentTableSeeder');
		// $this->call('MessageTableSeeder');
		// $this->call('LikeTableSeeder');
	}

}
