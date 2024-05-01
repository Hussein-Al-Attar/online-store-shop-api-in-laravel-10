<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create();
        $posts = Post::factory()
            ->count(3)
            ->for($user)
            ->create();
    }
}
