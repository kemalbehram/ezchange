<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Flysystem\Config;

class UserPaymentController extends Controller
{
    public function index()
    {
        $users = UserPayment::all();
        if ($users->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست پرداخت ها', 'data' => $users];
        } else {
            return ['status' => 'error', 'message' => 'پرداختی برای نمایش وجود ندارد', 'data' => null];
        }
    }

    public function pay_with_jibit(Request $request)
    {

        /* validate request data */
        /* get the authenticated users id */

        $data = array(
            'amount' => \request('amount'),
            'currency' => \request('currency'),
            'referenceNumber' => (Str::orderedUuid()),
            'userIdentifier' => \request('user_id'),
            'callbackUrl' => env('APP_API_URL') . 'check-payment-status',
            'forcePayerCardNumber' => false
        );
        /*
         * base url:
         * https://api.jibit.ir/ppg/v2/
         * */
        $access_token = (Configuration::where('title', '=', 'jibit_pay_access')->first())->value;
        $base_url = (Configuration::where('title', '=', 'jibitpay_base_url')->first())->value;
        $response = Http::withToken($access_token)->asJson()->post($base_url . 'orders', $data)->body();
        $response = json_decode($response, true);
//        dd($response);
        if (array_key_exists("errors", $response))
        {
            return ["status" => "error", "message" => "مشکلی در پرداخت به وجود آمده است", "data" => null];
        } else {
            /* save the order ref number */

            return ["status" => "error", "message" => "change window location to this url", "data" => ['redirect_url' => $response["pspSwitchingUrl"]]];
        }
    }

    public function check_payment_status(Request $request)
    {
        if ($request['state'] === 'SUCCESSFUL')
        {

        } else {

        }
    }

    public function pay_with_vendar()
    {

    }

    public function store(Request $request): array
    {

    }

    public function show(UserPayment $userPayment)
    {
        //
    }

    public function edit(UserPayment $userPayment)
    {
        //
    }

    public function update(Request $request, UserPayment $userPayment)
    {
        //
    }

    public function destroy(UserPayment $userPayment)
    {
        //
    }
}
