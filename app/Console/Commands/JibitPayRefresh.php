<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JibitPayRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jibitpay:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Jibit Pay tokens';

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
        $access_token = (Configuration::where('title', '=', 'jibit_pay_access')->first())->value;
        $refresh_token = (Configuration::where('title', '=', 'jibit_pay_refresh')->first())->value;
        $response = Http::asJson()->post('https://api.jibit.ir/ppg/v2/tokens/refresh', [
            "accessToken" => $access_token,
            "refreshToken" => $refresh_token
        ])->body();
        $tokens = json_decode($response, true);

        /* save tokens in the DB*/
        DB::table('configurations')->where('title', '=', 'jibit_pay_access')->update([
            'value' => $tokens['accessToken'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('configurations')->where('title', '=', 'jibit_pay_refresh')->update([
            'value' => $tokens['refreshToken'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
