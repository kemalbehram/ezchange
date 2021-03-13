<?php

namespace App\Http\Controllers;

use App\Models\SMS;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kavenegar\KavenegarApi;

class SMSController extends Controller
{

    public function verification_rejected(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'mobile_number'     => 'required|digits:11|starts_with:09|exists:users,mobile_number',
        ], [
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.exists'      => 'شماره موبایل وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $kavenegar = new KavenegarApi(env('KAVENEGAR_API_KEY'));
        $response = $kavenegar->VerifyLookup(\request('mobile_number'), 'www.ezchange.ir', null, null, 'ezchange-reject')[0];
        SMS::create([
            'template' => 'ezchange-reject',
            'messageid' => $response->messageid,
            'message' => $response->message,
            'status' => $response->status,
            'statustext' => $response->statustext,
            'sender' => $response->sender,
            'receptor' => $response->receptor,
            'date' => $response->date,
            'cost' => $response->cost
        ]);

        return ['status' => 'success', 'message' => 'پیام با موفقیت ارسال شد', 'data' => null];
    }

    public function verification_accepted(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'mobile_number'     => 'required|digits:11|starts_with:09|exists:users,mobile_number',
        ], [
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.exists' => 'شماره موبایل وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $kavenegar = new KavenegarApi(env('KAVENEGAR_API_KEY'));
        $response = $kavenegar->VerifyLookup(\request('mobile_number'), 'www.ezchange.ir', null, null, 'ezchange-accept')[0];
        SMS::create([
            'template' => 'ezchange-accept',
            'messageid' => $response->messageid,
            'message' => $response->message,
            'status' => $response->status,
            'statustext' => $response->statustext,
            'sender' => $response->sender,
            'receptor' => $response->receptor,
            'date' => $response->date,
            'cost' => $response->cost
        ]);

        return ['status' => 'success', 'message' => 'پیام با موفقیت ارسال شد', 'data' => null];
    }

    public function send_verification_code(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'mobile_number'     => 'required|digits:11|starts_with:09|exists:users,mobile_number',
        ], [
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.exists' => 'کاربری با شماره تلفن وارد شده وجود ندارد',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = User::where('mobile_number', '=', \request('mobile_number'))->first();
        if ($user) {
            /* save the hash of sent code in the db*/
            $token  = random_int(10000, 99999);
            $user->mobile_verification_hash = md5($token);
            $user->save();

            /* send the verification code */
            $kavenegar = new KavenegarApi(env('KAVENEGAR_API_KEY'));
            $response = $kavenegar->VerifyLookup($user->mobile_number, $token, null, null, 'ezchange-verify')[0];
            SMS::create([
                'template' => 'ezchange-pass',
                'messageid' => $response->messageid,
                'message' => $response->message,
                'status' => $response->status,
                'statustext' => $response->statustext,
                'sender' => $response->sender,
                'receptor' => $response->receptor,
                'date' => $response->date,
                'cost' => $response->cost,
            ]);
            return ['status' => 'success', 'message' => 'کد تأیید با موفقیت ارسال شد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'کاربری با شماره تلفن وارد شده وجود ندارد', 'data' => null];
        }
    }

    public function read_verification_code(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'phone_number'     => 'required|digits:11|starts_with:0|exists:users,phone_number',
        ], [
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره تلفن ثابت الزامی است',
            'mobile_number.digits'      => 'شماره تلفن وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره تلفن وارد شده نامعتبر است',
            'mobile_number.exists'      => 'شماره تلفن وارد شده نامعتبر است'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = User::where('phone_number', '=', \request('phone_number'))->first();
        if ($user) {
            /* save the hash of sent code in the db*/
            $token  = random_int(10000, 99999);
            $user->phone_verification_hash = md5($token);
            $user->save();

            /* send the verification code */
            $kavenegar = new KavenegarApi(env('KAVENEGAR_API_KEY'));
            $response = $kavenegar->VerifyLookup($user->mobile_number, $token, null, null, 'ezchange-pass')[0];
            SMS::create([
                'template' => 'ezchange-pass',
                'messageid' => $response->messageid,
                'message' => $response->message,
                'status' => $response->status,
                'statustext' => $response->statustext,
                'sender' => $response->sender,
                'receptor' => $response->receptor,
                'date' => $response->date,
                'cost' => $response->cost,
            ]);
            return ['status' => 'success', 'message' => 'کد تأیید با موفقیت ارسال شد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'شماره تلفن وارد شده در سامانه وجود ندارد', 'data' => null];
        }
    }

}
