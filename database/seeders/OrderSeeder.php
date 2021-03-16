<?php

namespace Database\Seeders;

use App\Models\Order;
use Faker\Factory;
use Illuminate\Database\Seeder;
use DB;
use Sentinel;

class AdminSeeder extends DatabaseSeeder
{

    public function run()
    {
        $faker = Factory::create();
        for ($counter = 0; $counter < 500; $counter++)
        {
            $amount = random_int(100, 1000);
            $price = random_int(230000, 300000);
            Order::create([
                'amount_in_tethers' => $amount,
                'amount_in_rials' => $amount * $price,
                'price_in_rials' => $price,
                'type' => array_rand(['buy', 'sell']),
                'status' => array_rand('banned', 'process', 'complete', 'unconfirmed')
            ]);
        }
    }
}
