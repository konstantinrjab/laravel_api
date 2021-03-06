<?php

use Illuminate\Database\Seeder;
use App\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::truncate();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 80; $i++) {
            Item::create([
                'category_id' => rand(1, 20),
                'name' => $faker->word,
                'sku' => $faker->uuid,
                'price' => rand(50, 500)
            ]);
        }
    }
}
