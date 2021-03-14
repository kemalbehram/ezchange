<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Sentinel;

class UsersSeeder extends DatabaseSeeder
{

    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $user = Sentinel::registerAndActivate([
                'email'     => $faker->unique()->email,
                'password'    => "password",
                'first_name' => $faker->unique()->firstName,
                'last_name' => $faker->unique()->lastName,
                'country'     => $faker->countryCode,
                'created_at' => $faker->dateTimeThisYear(),
                'mobile_number' => '0914'. random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9)
            ]);

            $user->roles()->attach(Sentinel::findRoleById(rand(1, 2)));
        }

        $this->command->info('Dummy users have been entered into database!');
    }
}
