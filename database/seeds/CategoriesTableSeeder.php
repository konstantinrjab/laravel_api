<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        
        for ($i = 1; $i <= 20; $i++) {
            Category::create([
                'name' => 'category_'.$i,
            ]);
        }
    }
}
