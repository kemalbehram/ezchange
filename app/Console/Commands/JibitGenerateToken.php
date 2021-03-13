<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JibitGenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jibit:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Jibit access and refresh tokens';

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
        $api_key = env('JIBIT_API_KEY');
        $secret_key = env('JIBIT_SECRET_KEY');
        $response = Http::asJson()->post('https://api.jibit.ir/aref/v1/tokens/generate', [
            "apiKey" => $api_key,
            "secretKey" => $secret_key
        ])->body();
        $tokens = json_decode($response, true);

        /* save tokens in the DB*/
        DB::table('configurations')->insert([
            [
                'title' => 'jibit_access',
                'value' => $tokens['accessToken'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'jibit_refresh',
                'value' => $tokens['refreshToken'],
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
