<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
            'category_name' => 'Category 1',
            'parent_category' => 0,
         ]);
         \App\Models\Category::create([
            'category_name' => 'Category 1-1',
            'parent_category' => 1,
         ]);
         \App\Models\Category::create([
            'category_name' => 'Category 1-2',
            'parent_category' => 1,
         ]);
    }
}
