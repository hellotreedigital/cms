<?php

use Illuminate\Database\Seeder;

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
