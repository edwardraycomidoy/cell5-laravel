<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Member;

class MembersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Member::factory(1000)->create();
	}
}
