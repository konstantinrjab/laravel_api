<?php

use Illuminate\Database\Seeder;
use App\ItemParameter;

class ItemParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemParameter::truncate();
        $faker = \Faker\Factory::create();
    
        for ($i = 0; $i < 20; $i++) {
            ItemParameter::create([
                'id_item' => rand(1, 20),
                'id_parameter' => rand(1, 20),
                'value' => ((rand(0, 1)) ? $faker->word : rand(20, 100))
            ]);
        }
    }
}
