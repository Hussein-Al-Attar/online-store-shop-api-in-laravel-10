<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facter=Factory::create();
        for ($i=0;$i<50;$i++){
            for ($j = 0; $j < 2; $j++) {
                $post= new Post([
                    'body' => $facter->name,
                    'user_id' => $i,
                ]);
                $post->save();
            }
        }
    }
}
