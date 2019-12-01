<?php

namespace Hellotreedigital\Cms\Seeds;

use Illuminate\Database\Seeder;
use DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('admins')->insert([
    		'name' => 'HELLOTREE',
    		'email' => 'support@hellotree.co',
    		'password' => bcrypt('$h1e2l3#'),
    	]);
    }
}
