<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class PartnerUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partner_users')->insert([
            'username' => str_random(32),
            'email' => 'partner@uangteman.com',
            'password' => app('hash')->make('uangteman'),
            'remember_token' => str_random(10),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
