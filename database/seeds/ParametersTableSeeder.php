<?php

use Illuminate\Database\Seeder;
use App\Parameter;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::truncate();
        
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            Parameter::create([
                'name' => $faker->word,
            ]);
        }
    }
}
