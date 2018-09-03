<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    private $_password;

    public function __construct()
    {
        $this->_password = Hash::make('secret_pass');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            User::truncate();
        } catch (Exception $e) {
            $this->_addAdmin();
            return;
        }
        $faker = \Faker\Factory::create();

        $this->_addAdmin();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $this->_password,
            ]);
        }
    }

    private function _addAdmin()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $this->_password,
            'api_token' => 'Buhq0cKoBoxWbOecK3oynYK536lXc6Kmi3hR90G5kD6rFB7FgBAkFYA1C8ZN'
        ]);
    }
}
