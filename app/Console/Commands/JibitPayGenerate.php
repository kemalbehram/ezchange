<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JibitPayGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jibitpay:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Jibit Pay tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $api_key = env('JIBIT_PAY_API_KEY');
        $secret_key = env('JIBIT_PAY_SECRET_KEY');
        $response = Http::asJson()->post('https://api.jibit.ir/ppg/v2/tokens/generate', [
            "apiKey" => $api_key,
            "secretKey" => $secret_key
        ])->body();
        $tokens = json_decode($response, true);

        /* save tokens in the DB*/
        DB::table('configurations')->insert([
            [
                'title' => 'jibit_pay_access',
                'value' => $tokens['accessToken'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'jibit_pay_refresh',
                'value' => $tokens['refreshToken'],
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
