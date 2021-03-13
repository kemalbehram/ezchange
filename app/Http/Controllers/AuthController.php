<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\SMS;
use App\Models\User;
use App\Models\UserDocument;
use App\Rules\IranID;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Kavenegar\KavenegarApi;
use Kavenegar\Laravel\Message\KavenegarMessage;
use phpseclib3\Crypt\Random;

class AuthController extends Controller
{

    public function login()
    {
        $validator = Validator::make(\request()->all(), [
            'mobile_number'     => 'required|digits:11|starts_with:09|exists:users,mobile_number',
            'password'          => 'required|string|min:6|max:64',
        ], [
            /* password custom validation messages */
            'password.required'  => 'وارد کردن رمز عبور الزامی است',
            'password.string'       => 'اطلاعات وارد شده نامعتبر است',
            'password.min'       => 'اطلاعات وارد شده نامعتبر است',
            'password.max'       => 'اطلاعات وارد شده نامعتبر است',
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'اطلاعات وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'اطلاعات وارد شده نامعتبر است',
            'mobile_number.exists' => 'اطلاعات وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        if (\request()->has('mobile_number'))
        {
            $user = User::where('mobile_number', '=', \request('mobile_number'))->first();
            if (Hash::check(\request('password'), $user->password))
            {
                $token = $user->createToken('ez_change')->accessToken;
                return ['status' => 'success', 'message' => 'با موفقیت وارد شده اید', 'data' => ['token' => $token]];
            } else {
                return ['status' => 'error', 'message' => 'اطلاعات وارد شده نامعتبر است', 'data' => 'null'];
            }
        } else {
            return ['status' => 'error', 'message' => 'اطلاعات وارد شده نامعتبر است', 'data' => 'null'];
        }
    }

    public static function unauthorized(): array
    {
        return ['status' => 'error', 'message' => 'برای دسترسی به این سرویس ابتدا وارد شوید', 'data' => ['token_status' => false]];
    }
    public function update_password()
    {
        $validator = Validator::make(\request()->all(), [
            'old_password'                      => 'required|string|min:6|max:64',
            'new_password'                  => 'required|confirmed|string|min:6|max:64',
            'new_password_confirmation'     => 'string|min:6|max:64',
        ], [
            /* old password */
            'old_password.required'  => 'وارد کردن رمز عبور الزامی است',
            'old_password.string'       => 'رمز عبور وارد شده نامعتبر است',
            'old_password.min'       => 'حداقل طول رمزعبور 6 کاراکتر است',
            'old_password.max'       => 'حداکثر طول رمزعبور 64 کاراکتر است',
            /* new password */
            'new_password.required'  => 'وارد کردن رمز عبور الزامی است',
            'new_password.confirmed'     => 'تأیید رمز عبور الزامی است',
            'new_password.string'       => 'رمز عبور وارد شده نامعتبر است',
            'new_password.min'       => 'حداقل طول رمزعبور 6 کاراکتر است',
            'new_password.max'       => 'حداکثر طول رمزعبور 64 کاراکتر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = User::find(auth()->id())->first();
        /* check if the old password is true */
        if (!Hash::check(\request('old_password'), $user->password))
        {
            return ['status' => 'error', 'message' => 'رمز عبور قبلی نادرست است', 'data' => $validator->errors()];
        }
        /* check if the new password is not the same with old password  */
        if (Hash::check(\request('new_password'), $user->password))
        {
            return ['status' => 'error', 'message' => 'رمز عبور وارد شده با رمز عبور قبلی یکسان است', 'data' => $validator->errors()];
        }
        /* Hash the password and save it */
        $user->password = Hash::make(\request('new_password'));
        $updated = $user->save();
        var_dump($updated);
        if ($updated) {
            return ['status' => 'success', 'message' => 'رمز عبور با موفقیت تغییر کرد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'تغییر رمز عبور ناموفق بود', 'data' => null];
        }
    }

    public function match_mobile_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'national_code' => ['required', new IranID],
            'mobile_number' => 'required|digits:11|starts_with:09'
        ], [
            /* national code custom validation messages */
            'national_code.required' => 'وارد کردن کد ملی الزامی است',
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $access_token = (Configuration::where('title', '=', 'jibit_access')->first())->value;
        $response = Http::withToken($access_token)->asJson()->post('https://api.jibit.ir/aref/v1/services/matchNationalCodeAndMobileNumber ', [
            'nationalCode' => \request('national_code'),
            'mobile' => \request('mobile_number'),
        ])->body();
        $response = json_decode($response, true);

        if (array_key_exists('errors', $response))
        {
            return ['status' => 'error', 'message' => 'مشکلی در استعلام شماره موبایل به وجود آمده است. لطفا مجدد امتحان نمایید', 'data' => null];
        } else {
            if ($response['matched'])
            {
                return ['status' => 'success', 'message' => 'شماره تلفن متعلق به کد ملی وارد شده می باشد', 'data' => null];
            } else {
                return ['status' => 'error', 'message' => 'شماره تلفن متعلق به کد ملی وارد شده نمی باشد', 'data' => null];
            }
        }
    }

    public function verify_reset_code()
    {
        $hash = md5(\request('code'));
        $user = User::where('reset_pass_hash', '=', $hash)->first();
        if ($user) {
            return ['status' => 'error', 'message' => 'کد وارد شده مورد تأیید می باشد', 'data' => $hash];
        } else {
            return ['status' => 'error', 'message' => 'کد وارد شده نامعتبر است', 'data' => null];
        }
    }

    public function verify_mobile_code(Request $request)
    {
        $hash = md5(\request('code'));
        $user = User::where('mobile_verification_hash', '=', $hash)->first();
        if ($user) {
            $user->mobile_is_verified = 1;
            $user->mobile_verified_at = now();
            $user->mobile_verification_hash = null;
            $user->save();
            return ['status' => 'error', 'message' => 'کد وارد شده مورد تأیید می باشد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'کد وارد شده نامعتبر است', 'data' => null];
        }
    }

    public function reset_password()
    {
        $validator = Validator::make(\request()->all(), [
            'hash_code'                     => 'required',
            'password'                  => 'required|confirmed|string|min:6|max:64',
            'password_confirmation'     => 'string|min:6|max:64',
        ], [
            /* password custom validation messages */
            'password.required'  => 'وارد کردن رمز عبور الزامی است',
            'password.confirmed'     => 'تأیید رمز عبور الزامی است',
            'password.string'       => 'رمز عبور وارد شده نامعتبر است',
            'password.min'       => 'حداقل طول رمزعبور 6 کاراکتر است',
            'password.max'       => 'حداکثر طول رمزعبور 64 کاراکتر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = User::where('reset_pass_hash', '=', \request('hash_code'))->first();
        $user->password = Hash::make(\request('password'));
        $user->save();

        return ['status' => 'success', 'message' => 'رمز عبور با موفقیت تغییر کرد', 'data' => null];
    }

    public function logout()
    {
        $token = \request()->user()->token();
        $token->revoke();
        $response = ['status' => 'error', 'message' => 'با موفقیت خارج شدید', 'data' => null];
        return response($response, 200);
    }

    public function check_name_similarity(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'full_name'                => 'regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'first_name'                => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'last_name'                 => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'parent_name'               => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            /* date validation */
            'birthdate'                 => 'required|jdate',
            'national_code'             => ['required', new IranID, 'unique:users,national_code'],
        ], [
            /* first name custom validation messages */
            'full_name.required'       => 'وارد کردن نام الزامی است',
            'full_name.regex'          => 'نام وارد شده نامعتبر است',
            'full_name.min'            => 'حداقل طول نام 2 کاراکتر است',
            'full_name.max'            => 'حداکثر طول نام 255 کاراکتر است',
            /* first name custom validation messages */
            'first_name.required'       => 'وارد کردن نام الزامی است',
            'first_name.regex'          => 'نام وارد شده نامعتبر است',
            'first_name.min'            => 'حداقل طول نام 2 کاراکتر است',
            'first_name.max'            => 'حداکثر طول نام 255 کاراکتر است',
            /* last name custom validation messages */
            'last_name.required'        => 'وارد کردن نام خانوادگی الزامی است',
            'last_name.regex'           => 'نام خانوادگی وارد شده نامعتبر است',
            'last_name.min'             => 'حداقل طول نام خانوادگی 2 کاراکتر است',
            'last_name.max'             => 'حداکثر طول نام خانوادگی 255 کاراکتر است',
            /* parent name custom validation messages */
            'parent_name.required'  => 'وارد کردن نام پدر الزامی است',
            'parent_name.regex'     => 'مقدار وارد شده نامعتبر است',
            'parent_name.min'       => 'حداقل طول نام پدر 2 کاراکتر است',
            'parent_name.max'       => 'حداکثر طول نام پدر 255 کاراکتر است',
            /* national code custom validation messages */
            'national_code.required' => 'وارد کردن کد ملی الزامی است',
            'national_code.unique' => 'کد ملی وارد شده قبلا ثبت شده است',
            /* birthdate custom validation messages */
            'birthdate.required' => 'وارد کردن تاریخ تولد الزامی است',
            'birthdate.jdate' => 'تاریخ وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

//        dd($request->all());
//        $request->merge([
//            'first_name' => 'علی',
//            'last_name' => 'جلیل وند شادباد'
//        ]);
        $access_token = (Configuration::where('title', '=', 'jibit_access')->first())->value;
        $data = array(
            'nationalCode'  => \request('national_code'),
            'birthDate'     => \request('birthdate'),
            'firstName'     => \request('first_name'),
            'lastName'      => \request('last_name'),
            'fatherName'    => \request('parent_name')
        );

        if (isset($request->full_name))
        {
            $data['fullName'] = \request('full_name');
        }
        $response = Http::withToken($access_token)->asJson()->post('https://api.jibit.ir/aref/v1/services/nameSimilarity ', $data)->body();
        $similarity = json_decode($response, true);


        if ($similarity['firstNameSimilarity'] === 100 and $similarity['lastNameSimilarity'] === 100)
        {
            return ['status' => 'success', 'message' => 'اطلاعات وارد شده با اطلاعات ثبت احوال مطابقت دارد', 'data' => null];
        } else {
            return ['status' => 'error', 'message' => 'اطلاعات وارد شده با اطلاعات ثبت احوال مطابقت ندارد', 'data' => null];
        }
    }
}
