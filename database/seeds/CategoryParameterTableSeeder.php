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

        for ($i = 0; $i < 80; $i++) {
            $value = (rand(0,1) ? $faker->word : rand(20,100));
            CategoryParameter::create([
                'category_id' => rand(1, 30),
                'parameter_id' => rand(1, 30),
            ]);
        }
    }
}
