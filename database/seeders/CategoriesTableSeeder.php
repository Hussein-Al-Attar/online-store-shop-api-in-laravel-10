<?php

namespace Database\Seeders;
use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Seed categories data
         Category::create(['name' => 'Electronics']);
         Category::create(['name' => 'Clothing']);
    }
}
