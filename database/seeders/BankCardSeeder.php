<?php

namespace Database\Seeders;

use App\Models\BankCard;
use Faker\Factory;
use Illuminate\Database\Seeder;

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
                $amount = random_int(50, 500);
                $price = random_int(230, 300);
                BankCard::create([
                    'user_id' => $id,
                    'amount_in_tethers' => $amount,
                    'amount_in_rials' => $amount * $price,
                    'price_in_rials' => $price,
                    'tx_id' => '1234567890',
                    'pay_time' => now(),
                    'payment_status' => '0',
                    'type' => 'buy',
                    'status' => 'process',
                    'psp_url' => $faker->url,
                ]);
            }
        }
    }
}