<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(CategoriesTableSeeder::class);
        $this->call(CategoryParameterTableSeeder::class);
        $this->call(ItemParameterTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(ParametersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
