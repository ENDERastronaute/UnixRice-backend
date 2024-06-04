<?php

namespace Database\Factories;

use App\Models\User;
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
    public function definition(): array
    {
        return [
            'channel_id' => random_int(1, 2),
            'author_id' => User::factory(),
            'content' => json_encode([
                'title' => fake()->realTextBetween(5, 20),
                'paragraph' => fake()->realTextBetween(10, 30),
                'images' => []
            ])
        ];
    }
}
