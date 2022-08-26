<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create()->each(function ($u) {
            factory(App\Post::class, 5)->create()->each(
                function($p) use (&$u) {
                    $u->posts()->save($p)->make();
                }
            );
        });
    }
}
