<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Str;
use Sentinel;

class OrderSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('orders')->truncate();
        $faker = Factory::create();

        $users = User::all()->pluck('id');
        foreach ($users as $id)
        {
            for ($counter = 0; $counter < random_int(5, 8); $counter++)
            {
                $amount = random_int(50, 500);
                $price = random_int(230, 300);
                Order::create([
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
