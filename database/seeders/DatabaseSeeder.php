<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BankCardSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
