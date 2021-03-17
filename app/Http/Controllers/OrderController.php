<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use phpDocumentor\Reflection\Types\This;
use PhpParser\Node\Expr\PreDec;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function data_table()
    {
        $orders = Order::all(['id', 'amount_in_tethers', 'amount_in_rials', 'price_in_rials','created_at', 'type', 'payment_status', 'pay_time']);

//        dd($orders);
        return DataTables::of($orders)
            ->editColumn(
                'created_at',
                function (Order $order) {
                    return $order->created_at->diffForHumans();
                }
            )
            ->addColumn(
                'actions',
                function (Order $order) {
                    $actions = '<a href='. route('admin.orders.show', $order->id) .'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view user"></i></a>
                            <a href='. route('admin.orders.edit', $order->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update user"></i></a>';
                    return $actions;
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function index(): array
    {
        $orders = Order::all();
        if ($orders->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست سفارشات', 'data' => Order::all()];
        } else {
            return ['status' => 'error', 'message' => 'سفارشی موجود نیست', 'data' => null];
        }
    }

    public function store(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|regex:/^[0-9]{1,6}\.{0,1}[0-9]{0,8}$/',
            'type' => 'in:1,2',
        ], [
            /* amount custom validation error messages */
            'amount.required' => 'وارد کردن مقدار سفارش الزامی است',
            'amount.regex' => 'مقدار وارد شده نامعتبر است',
            /* type custom validation error messages */
//            'type.required' => 'وارد کردن نوع سفارش الزامی است',
            'type.in' => 'نوع سفارش نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

//        $order_checkout = $this->calculate_order_total($request);
//        if ($order_checkout['status'] === 'error')
//        {
//            return $order_checkout;
//        } else {
//            $order_checkout = $order_checkout['data'];
//        }

        $user_id = auth()->id();
        if ($user_id === null)
        {
            return AuthController::unauthorized();
        }

        $order_attributes = [
            'user_id' => $user_id,
            'amount_in_tethers' => floatval(\request('amount')),
            'type' => \request('type')
        ];
//        if (\request('type') == 1)
//        {
//            $order['to_wallet'] = \request('wallet_address');
//        } else {
//            $order['from_wallet'] = \request('wallet_address');
//        }
        $order = Order::create($order_attributes);

        if ($order)
        {
            return ['status' => 'success', 'message' => 'آدرس کیف پول را وارد کنید', 'data' => ['order_id' => $order->id]];
        } else {
            return ['status' => 'error', 'message' => 'مشکلی در ثبت سفارش به وجود آمده است لطفا بعدا امتحان نمایید', 'data' => null];
        }
    }

    public function get_user_wallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'wallet_address' => 'required'
        ], [
            'order_id.required' => 'وارد کردن شناسه سفارش الزامی است',
            'order_id.exists' => 'شناسه سفارش وارد شده نامعتبر است',
            'wallet_address.required' => 'وارد کردن آدرس کیف پول الزامی است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }


        /*
         * extract order from db - done
         * send a request to jibit and get response url - done
         * save jibit response to db - done
         * return url as a response - done
         *
         * */

        /* send a payment request to jibit */
        $order = Order::find(\request('order_id'));

        if ($order === null)
        {
            return ['status' => 'error', 'message' => 'مشکلی در پردازش سفارش شما به وجود آمده است', 'data' => null];
        } else {
            if ($order->type === "buy")
            {
                $current_price = $this->tether_current_price();
                if ($current_price['status'] === 'error')
                {
                    return $current_price;
                } else {
                  $current_price = $current_price['data'];
                }

                /* calculate total cost of order based on the current price */
                $total_cost = $order->amount_in_tethers * $current_price;
                /* send a request to jibit to get order info */
                $tmp = $this->send_order_request_to_jibit($total_cost, $order->user_id);

                if ($tmp['status'] === 'error') return $tmp;

                $tmp = $tmp['data'];
                $redirect_url = $tmp['pspSwitchingUrl'];
                /* save order info to db */
                $order->price_in_rials = $current_price;
                $order->amount_in_rials = $total_cost;
                $order->to_wallet = \request('wallet_address');
                $order->order_identifier = $tmp['orderIdentifier'];
                $order->reference_number = $tmp['referenceNumber'];
                $order->psp_url = $tmp['pspSwitchingUrl'];
                $tmp = $order->save();

                if ($tmp)
                    return ['status' => 'success', 'message' => 'لینک پرداخت هزینه سفارش', 'data' => ['redirect_url' => $redirect_url]];
                else
                    return ['status' => 'error', 'message' => 'مشکلی در پردازش سفارش شما به وجود آمده است', 'data' => null];
            } else if ($order->type === "sell") {
                /* if order type is sell */
                $order->from_wallet = \request('wallet_address');
                $order->save();
            }
            return ['status' => 'success', 'message' => 'مشکلی در پردازش سفارش شما به وجود آمده است', 'data' => null];
        }
    }

    public function send_order_request_to_jibit($amount, $user_identifier, $currency = 'TOMAN', $callback_url = null): array
    {
        $reference_number = Str::orderedUuid()->getInteger()->toString();
        if ($callback_url === null or '')
        {
            $callback_url = route('check-payment-status');
        }

        $data = array(
            'amount' => $amount,
            'currency' => $currency,
            'referenceNumber' => $reference_number,
            'userIdentifier' => $user_identifier,
            'callbackUrl' => $callback_url,
            'forcePayerCardNumber' => false
        );
        $access_token = (Configuration::where('title', '=', 'jibit_pay_access')->first())->value;
        $base_url = (Configuration::where('title', '=', 'jibitpay_base_url')->first())->value;
        $response = Http::withToken($access_token)->asJson()->post($base_url . 'orders', $data)->body();
        $response = json_decode($response, true);
//        dd($response);
        if (array_key_exists("errors", $response))
        {
            return ["status" => "error", "message" => "مشکلی در پرداخت به وجود آمده است", "data" => null];
        } else {
            return ["status" => "success", "message" => "change window location to this url", "data" => $response];
        }
    }
    /* for user to pay the order */
    public function checkout(Request $request)
    {

    }

    public function set_binance_order()
    {

    }

    /* for admin to pay the rewards */
    public function invoice()
    {

    }


    public function calculate_order_total(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|regex:/^[0-9]+\.{0,1}[0-9]{0,8}$/'
        ], [
            /* amount custom validation error messages */
            'amount.required' => 'وارد کردن مقدار سفارش الزامی است',
            'amount.regex' => 'مقدار وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }


        $order_amount = floatval(\request('amount'));
        /* check if order amount is more than min order amount */
        $min_allowed_amount = Configuration::where('title', '=', 'min_order_amount')->first();
        if ($min_allowed_amount)
        {
            $min_allowed_amount = floatval($min_allowed_amount->value);
            /* check if amount is gt min allowed amount */
            if ($order_amount >= $min_allowed_amount)
            {
                $current_price = Configuration::where('title', '=', 'tether_price')->first();
                if (!$current_price)
                {
                    return ['status' => 'error', 'message' => 'مشکلی در محاسبه قیمت به وجود آمده است', 'data' => null];
                } else {
                    $current_price = floatval($current_price->value);
                    $order_total = $order_amount * $current_price;
                    return ['status' => 'success', 'message' => 'مبلغ کل سفارش', 'data' => $order_total];
                }
            } else {
                return ['status' => 'error', 'message' => 'حداقل مقدار سفارش ' . $min_allowed_amount . ' تتر است', 'data' => null];
            }
        } else {
            return ['status' => 'error', 'message' => 'مشکلی در محاسبه قیمت به وجود آمده است', 'data' => null];
        }
    }

    public function tether_current_price(): array
    {
        $current_price = Configuration::where('title', '=', 'tether_price')->first();
        if ($current_price)
        {
            return ['status' => 'success', 'message' => 'قیمت فعلی تتر', 'data' => $current_price->value];
        } else {
            return ['status' => 'error', 'message' => 'مشکلی در محاسبه قیمت به وجود آمده است', 'data' => null];
        }
    }

    public function show(Order $order)
    {
        return view('admin.orders.show')->with('order', $order);
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }
}
