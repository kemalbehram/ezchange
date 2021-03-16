<?php

namespace Database\Seeders;

use App\Models\BankCard;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankCardSeeder extends Seeder
{
    public function run()
    {
        DB::table('bank_cards')->truncate();
        $faker = Factory::create();

        $users = User::all()->pluck('id');
        foreach ($users as $id)
        {
            for ($counter = 0; $counter < random_int(5, 8); $counter++)
            {
                BankCard::create([
                    'status' => 1,
                    'bank' => 'mellat',
                    'user_id' => 1,
                    'account_number' => '782649234362',
                    'card_number' => '1234567891234567',
                    'deposit' =>'0',
                    'withdraw' => '1',
                    'owner_first_name' => $faker->firstName,
                    'owner_last_name' => $faker->lastName,
                    'iban' => '5646812316'
                ]);
            }
        }
    }
}
