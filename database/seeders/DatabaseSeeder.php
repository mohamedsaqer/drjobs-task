<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Create Admin
         User::factory()->create([
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'email_verified_at' => now(),
             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
             'role' => 'admin',
             'status' => 'active',
         ]);
        $this->call(UserSeeder::class);
        $this->call(PostCategorySeeder::class);
        $this->call(PostSeeder::class);
    }
}
