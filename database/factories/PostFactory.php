<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'       =>  rand(1, 100),
            'title'         => $this->faker->jobTitle(),
            'content'       => $this->faker->text(500),
            'post_category' => rand(1, 5),
            'status'        => $this->faker->randomElement(['draft','publish','private']),
        ];
    }
}
