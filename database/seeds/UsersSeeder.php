<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the admin user
        \App\User::insert([
            ['name' => 'Admin', 'email' => env('ADMIN_USER'), 'email_verified_at' =>\Carbon\Carbon::now(), 'password' => bcrypt('123456'), 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]
        ]);
    }
}
