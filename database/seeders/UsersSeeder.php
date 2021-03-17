<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sentinel;
use function Livewire\str;

class UsersSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('users')->truncate();

        $faker = Factory::create();
        $status = ['process', 'banned', 'verified'];
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'email'     => $faker->unique()->email,
                'password'    => "password",
                'first_name' => $faker->unique()->firstName,
                'last_name' => $faker->unique()->lastName,
                'is_verified' => str($status[array_rand($status)]),
                'national_code' => str(random_int(1000000, 9000000)),
                'full_name' => $faker->name(),
                'country'     => $faker->countryCode,
                'created_at' => $faker->dateTimeThisYear(),
                'mobile_number' => '0914'. random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9) . random_int(0,9)
            ]);
        }

        $this->command->info('Dummy users have been entered into database!');
    }
}
