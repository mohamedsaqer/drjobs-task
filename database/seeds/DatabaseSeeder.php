<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default active Admin
        \App\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'), // password
            'remember_token' => str_random(10),
            'role' => 'admin',
            'status' => 'active',
        ]);
        // Create default active User
        \App\User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'), // password
            'remember_token' => str_random(10),
            'role' => 'user',
            'status' => 'active',
        ]);
         $this->call(UserSeeder::class);
         $this->call(PostCategorySeeder::class);
         $this->call(PostSeeder::class);
    }
}
