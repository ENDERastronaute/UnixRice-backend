<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        User::all()->each(function ($user) {
            Post::factory()->count(random_int(3, 10))->create([
                'author_id' => $user->id
            ]);
        });

        Post::all()->each(function ($post) {
            Vote::factory()->count(random_int(1, 100))->create([
                'post_id' => $post->id
            ]);
        });
    }
}
