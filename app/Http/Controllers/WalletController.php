<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{

    public function random_wallet()
    {

    }

    public function index()
    {
        $wallets = Wallet::all();
        if ($wallets->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست کیف پول ها', 'data' => $wallets];
        } else {
            return ['status' => 'error', 'message' => 'کیف پولی موجود نیست', 'data' => null];
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required',
            'network_type' => 'required,in:erc20,trc20'
        ], [
            'wallet_address.required' => 'وارد کردن آدرس کیف پول الزامی است',
            'network_type.required' => 'انتخاب نوع شبکه الزامی است',
            'network_type.in' => 'شبکه انتخاب شده نامعتبر است'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => null];
        }

//        Wallet::create
    }

    public function get_wallets_data()
    {
        $url = 'https://crm.navid.trade/getAddress';
        $response = Http::get($url)->body();
        $data = json_decode($response, true);
        foreach ($data as $d)
        {
            foreach ($d as $network => $wal)
            {
                $in_db = Wallet::where('wallet_address', '=', $wal['address'])->where('type', '=', $network)->first();
                if ($in_db === null)
                {
                    $wallet = new Wallet();
                    $wallet->wallet_address = $wal['address'];
                    $wallet->type = $network;
                    $wallet->coin = $wal['coin'];
                    $wallet->tag = $wal['tag'];
                    $wallet->url = $wal['url'];
                    $wallet->save();
                }
            }
        }
        return ['status' => 'success', 'message' => 'کبف پول های جدید با موفقیت اضافه شدند', 'data' => null];
    }

    public function select_random_wallet()
    {
        /* no idea */
        DB::table('wallets')->select('wallets.id')
            ->join('wallet_lock_histories', 'wallet_lock_histories.wallet_id', '=', 'wallets.id')
            ->where('wallets.is_locked', '=', 0);
    }
    public function show(Wallet $wallet)
    {
        //
    }

    public function edit(Wallet $wallet)
    {
        //
    }

    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    public function destroy(Wallet $wallet)
    {
        //
    }
}
