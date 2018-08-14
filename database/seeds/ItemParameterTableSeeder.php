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
    
        for ($i = 0; $i < 200; $i++) {
            ItemParameter::create([
                'item_id' => rand(1, 30),
                'parameter_id' => rand(1, 30),
                'value' => ((rand(0, 1)) ? $faker->word : rand(20, 100))
            ]);
        }
    }
}
