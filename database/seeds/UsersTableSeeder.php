<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('toptal');

        try {
            User::truncate();
        } catch (Exception $e) {
            $this->_addAmin($password);
            return;
        }
        $faker = \Faker\Factory::create();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
            'api_token' => 'Buhq0cKoBoxWbOecK3oynYK536lXc6Kmi3hR90G5kD6rFB7FgBAkFYA1C8ZN'
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);
        }
    }

    private function _addAmin($password)
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
            'api_token' => 'Buhq0cKoBoxWbOecK3oynYK536lXc6Kmi3hR90G5kD6rFB7FgBAkFYA1C8ZN'
        ]);
    }
}
