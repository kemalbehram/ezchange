<?php

namespace App\Http\Controllers;

use App\Models\BankCard;
use App\Models\Configuration;
use App\Models\User;
use Egulias\EmailValidator\Exception\LocalOrReservedDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class BankCardController extends Controller
{
    public function data_table()
    {
        $cards = BankCard::all(['id', 'owner_first_name', 'owner_last_name', 'bank','account_number', 'card_number', 'iban', 'is_verified', 'deposit', 'withdraw', 'status', 'created_at']);

        return DataTables::of($cards)
            ->editColumn(
                'created_at',
                function (BankCard $card) {
                    return verta($card->created_at)->format('H:m:s Y-m-d');
                }
            )
            ->addColumn(
                'full_name',
                function (BankCard $card) {
                    return $card->owner_first_name . $card->owner_last_name;
                }
            )
            ->addColumn(
                'actions',
                function (BankCard $card) {
                    $actions = '<a href='. route('admin.bankcards.show', $card->id) .'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view user"></i></a>
                            <a href='. route('admin.bankcards.edit', $card->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update user"></i></a>';
                    return $actions;
                }
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function index()
    {
        $cards = BankCard::all();
        if ($cards->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست کارت های بانکی', 'data' => $cards];
        } else {
            return ['status' => 'error', 'message' => 'کارتی برای نمایش وجود ندارد', 'data' => null];
        }
    }

    /* get user cards based on token in user panel */
    public function get_user_cards(Request $request)
    {
        $u_id = $request->user()->id;
        $cards = BankCard::where('user_id', '=', $u_id)->get();

        if ($cards->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست کارت های بانکی کاربر', 'data' => $cards];
        } else {
            return ['status' => 'error', 'message' => 'کارتی برای نمایش وجود ندارد', 'data' => null];
        }
    }

    /* get user cards based on user_id in admin panel */
    public function get_user_cards_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ], [
            'user_id.required'  => '',
            'user_id.exists'    => ''
        ]);
    }


    public function store(Request $request): array
    {
        /*
         * 1. get card number
         * 2. get iban from card number
         * 3. validate f_name & l_name
         * 4. set deposit & withdraw bits based on the name similarity
         * 3. */
        $validator = Validator::make(\request()->all(), [
            'card_number' => 'required|digits:16',
//            'iban' => 'required|regex:/^IR[0-9]{24}$/'
        ], [
            /* card number custom validation errors */
            'card_number.required'   => 'وارد کردن شماره کارت الزامی است',
            'card_number.digits'   => 'شماره کارت وارد شده نامعتبر است',
            /* iban custom validation errors */
//            'iban.required'          => 'وارد کردن شماره شبا الزامی است',
//            'iban.regex'          => 'شماره شبا وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

//        $iban_info = $this->get_iban_info($request);
//        if ($iban_info['status'] === 'error')
//        {
//            return $iban_info;
//        } else {
//            $iban_info = $iban_info['data'];
//        }

        $card_info = $this->get_card_info($request);
        if ($card_info['status'] === 'error')
        {
            return $card_info;
        } else {
            $card_info = $card_info['data'];
        }

        $user = auth()->user();
        if ($user->first_name === $card_info['firstName'] and $user->last_name === $card_info['lastName'])
        {
            /* deposit and withdraw */
            BankCard::create([
                'user_id' => $user->id,
                'owner_first_name' => $card_info['firstName'],
                'owner_last_name' => $card_info['lastName'],
                'bank' => $card_info['bank'],
                'card_number' => \request('card_number'),
                'iban' => $card_info['iban'],
                'account_number' => $card_info['accountNumber'],
                'deposit' => 1,
                'withdraw' => 1
            ]);
        } else {
            /* withdraw only */
            BankCard::create([
                'user_id' => $user->id,
                'owner_first_name' => $card_info['firstName'],
                'owner_last_name' => $card_info['lastName'],
                'bank' => $card_info['bank'],
                'card_number' => \request('card_number'),
                'iban' => $card_info['iban'],
                'account_number' => $card_info['accountNumber'],
                'deposit' => 0,
                'withdraw' => 1
            ]);
        }
        return ['status' => 'success', 'message' => 'کارت با موفقیت به لیست کارت های شما اضافه شد', 'data' => false];
    }

    public function get_iban_info(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'iban' => 'required|regex:/^IR[0-9]{24}$/'
        ], [
            /* iban custom validation errors */
            'iban.required'          => 'وارد کردن شماره شبا الزامی است',
            'iban.regex'          => 'شماره شبا وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $access_token = (Configuration::where('title', '=', 'jibit_access')->first())->value;
        $iban_info = Http::withToken($access_token)->asJson()->post('https://api.jibit.ir/aref/v1/services/ibanInfo', [
            'iban' => $request->get('iban')
        ])->body();
        $iban_info = json_decode($iban_info, true);
        if (array_key_exists('errors', $iban_info))
        {
            foreach ($iban_info['errors'] as $error)
            {
                if ($error['message'] === 'no.provider.available.at.the moment')
                {
                    return ['status' => 'error', 'message' => 'شماره شبا وارد شده نامعتبر است', 'data' => null];
                }
            }
        }

        return ['status' => 'success', 'message' => 'اطلاعات حساب با موفقیت دریافت شد', 'data' => $iban_info];
    }

    public function get_card_info(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|digits:16',
        ], [
            /* card number custom validation errors */
            'card_number.required'   => 'وارد کردن شماره کارت الزامی است',
            'card_number.digits'   => 'شماره کارت وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $access_token = (Configuration::where('title', '=', 'jibit_access')->first())->value;
        $card_info = Http::withToken($access_token)->asJson()->post('https://api.jibit.ir/aref/v1/services/cardToIbanInfo', [
            'cardNumber' => $request->get('card_number')
        ])->body();
        $card_info = json_decode($card_info, true);

        if (array_key_exists('errors', $card_info))
        {
            foreach ($card_info['errors'] as $error)
            {
                if ($error['message'] === 'card.deposit.number.not.available')
                {
                    return ['status' => 'error', 'message' => 'شماره کارت وارد شده نامعتبر است', 'data' => null];
                }
            }
        }

        return ['status' => 'success', 'message' => 'اطلاعات حساب با موفقیت دریافت شد', 'data' => $card_info];
    }

    /* can a user edit a bank card */
    public function edit(BankCard $bankCard)
    {
        //
    }

    public function destroy(Request $request)
    {
        /*
         * get user id
         * get card number
         * get */
        $validator = Validator::make(\request()->all(), [
            'card_number' => 'required|digits:16',
        ], [
            /* card number custom validation errors */
            'card_number.required'   => 'وارد کردن شماره کارت الزامی است',
            'card_number.digits'   => 'شماره کارت وارد شده نامعتبر است'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        /*user_id = auth->id*/
        $tmp = BankCard::where('user_id', '=', 1)->where('card_number', '=', $request->get('card_number'))->delete();
        if ($tmp === 1)
        {
            return ['status' => 'success', 'message' => 'کارت موردنظر با موفقیت حذف شد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'حذف کارت ناموفق بود', 'data' => null];
        }
    }

    public function get_card_iban(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|digits:16'
        ], [
            'card_number.required'  => 'وارد کردن شماره کارت الزامی است',
            'card_number.digits'    => 'شماره کارت وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $access_token = (Configuration::where('title', '=', 'jibit_access')->first())->value;
        $card_info = Http::withToken($access_token)->asJson()->post('https://api.jibit.ir/aref/v1/services/cardToIbanInfo', [
            'cardNumber' => $request->get('card_number')
        ])->body();
        $card_info = json_decode($card_info, true);

        if (array_key_exists('errors', $card_info))
        {
            foreach ($card_info['errors'] as $error)
            {
                if ($error['message'] === 'card.deposit.number.not.available')
                {
                    return ['status' => 'error', 'message' => 'شماره کارت وارد شده نامعتبر است', 'data' => null];
                }
            }
        }

        return ['status' => 'success', 'message' => 'شماره شبا با موفقیت دریافت شد', 'data' => $card_info];

    }
}
