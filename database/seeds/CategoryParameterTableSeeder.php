<?php

use Illuminate\Database\Seeder;
use App\CategoryParameter;

class CategoryParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $value = (rand(0,1) ? $faker->word : rand(20,100));
        
        for ($i = 0; $i < 20; $i++) {
            CategoryParameter::create([
                'id_item' => rand(1, 20),
                'id_parameter' => rand(1, 20),
                'value' => $value
            ]);
        }
    }
}
