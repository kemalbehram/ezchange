<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use App\Models\UserDocument;
use App\Rules\IranID;
use Defuse\Crypto\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function index(): array
    {
        $users = User::all();
        if ($users->isNotEmpty())
        {
            return ['status' => 'success', 'message' => 'لیست کاربران', 'data' => $users];
        } else {
            return ['status' => 'error', 'message' => 'کاربری موجود نیست', 'data' => null];
        }
    }

    /* the first time user register with mobile number */
    public function store(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'mobile_number'             => 'required|digits:11|starts_with:09|unique:users,mobile_number',
            'password'                  => 'required|confirmed|string|min:6|max:64',
            'password_confirmation'     => 'string|min:6|max:64',
            'full_name'                 => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'national_code'             => ['required', new IranID, 'unique:users,national_code'],
            'referral_code'             => 'digits:6|nullable'
        ], [
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'اطلاعات وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'اطلاعات وارد شده نامعتبر است',
            'mobile_number.unique' => 'کاربری با مشخصانت وارد شده موجود است',
            /* password custom validation messages */
            'password.required'  => 'وارد کردن رمز عبور الزامی است',
            'password.confirmed'     => 'تأیید رمز عبور الزامی است',
            'password.string'       => 'رمز عبور وارد شده نامعتبر است',
            'password.min'       => 'حداقل طول رمزعبور 6 کاراکتر است',
            'password.max'       => 'حداکثر طول رمزعبور 64 کاراکتر است',
            /* full name custom validation errors */
            'full_name.required'        => 'وارد کردن نام خانوادگی الزامی است',
            'full_name.regex'           => 'اطلاعات وارد شده نامعتبر است',
            'full_name.min'             => 'اطلاعات وارد شده نامعتبر است',
            'full_name.max'             => 'اطلاعات وارد شده نامعتبر است',
            /* national code custom validation errors */
            'national_code.required' => 'وارد کردن کد ملی الزامی است',
            'national_code.unique' => 'کاربری با مشخصانت وارد شده موجود است',
            /* referral code custom validation errors */
            'referral_code.digits' => 'کد معرف وارد شده نامعتبر است'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }
        /* match mobile number and national_code */
        $tmp = (new AuthController())->match_mobile_id($request);
        if ($tmp['status'] === 'error')
        {
            return $tmp;
        }

        $field_verification = array(
            'first_name'        => 0,
            'last_name'         => 0,
            'parent_name'       => 0,
            'birthdate'         => 0,
            'national_code'     => 0,
            'phone_number'      => 0,
            'selfie_img'        => 0,
            'id_img'            => 0,
            'site_img'          => 0,
            'email'             => 0,
        );

        $referrer_id = User::where('referral_code', '=', \request('referral_code'))->first();
        if ($referrer_id)
        {
            $request->merge([
                'referral_id' => $referrer_id->id
            ]);
        } else {
            $request->merge([
                'referral_id' => null
            ]);
        }

        $u_id = User::create([
            'full_name' => \request('full_name'),
            'mobile_number' => \request('mobile_number'),
            'password' => Hash::make(\request('password')),
            'field_verification' => json_encode($field_verification),
            'referrer_id' => \request('referral_id')
        ])->pluck('id')[0];

        $request->merge([
            'user_id' => $u_id
        ]);
        (new SMSController())->send_verification_code();
        if ($u_id)
        {
            return ['status' => 'success', 'message' => 'ثبت نام شما با موفقیت انجام شد. کد تأیید به شماره شما ارسال شد', 'data' => null];
        } else {
            return ['status' => 'success', 'message' => 'مشکلی در ثبت نام به وجود آمده است لطفا مجددا امتحان نمایید', 'data' => null];
        }
    }

    public function update_profile(Request $request): array
    {
        /*
         * get user info based on user id
         * extract verified fields from there
         * if a requested field has been verified then it's not allowed to be changed
         * else validate it
         * save the validated fields in an array
         * updated the user based on changed fields
         * */
//        $validator = Validator::make($request->all(), [
//            'user_id' => 'required|exists:users,id'
//        ], [
//            /* user id custom validation */
//            'user_id.required'       => 'وارد کردن شناسه کاربری الزامی است',
//            'user_id.exists'          => 'کاربری با شناسه وارد شده موجود نیست',
//        ]);
//
//        if ($validator->fails())
//        {
//            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
//        }

        $user = (\auth()->user())->toArray();

        /* verification status of the fields */
        $verification_status = json_decode($user['field_verification'], true);

        /* rules to validate user inputs */
        $validation_rules = [
            'user_id'                   => ['user_id' =>'required|exists:users,id'],
            'first_name'                => ['first_name' =>'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255'],
            'last_name'                 => ['last_name' =>'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255'],
            'parent_name'               => ['parent_name' =>'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255'],
            'birthdate'                 => ['birthdate' =>'required|jdate'],
            'national_code'             => ['national_code' =>['required', new IranID, 'unique:users,national_code']],
            'phone_number'              => ['phone_number' =>'required|digits:11|starts_with:0'],
            'selfie_img'                => ['selfie_img' =>'required|file|mimes:jpg,jpeg,png,bmp|max:20480'],
            'id_img'                    => ['id_img' =>'required|file|mimes:jpg,jpeg,png,bmp|max:20480'],
            'site_img'                  => ['site_img' =>'required|file|mimes:jpg,jpeg,png,bmp|max:20480'],
//            'email'                     => ['email' =>'email:rfc,dns|unique:users,email']
        ];

        /* custom validation messages */
        $validation_messages = [
            /* first name custom validation messages */
            'first_name' => [
                'first_name.required'       => 'وارد کردن نام الزامی است',
                'first_name.regex'          => 'نام وارد شده نامعتبر است',
                'first_name.min'            => 'حداقل طول نام 2 کاراکتر است',
                'first_name.max'            => 'حداکثر طول نام 255 کاراکتر است'
            ],
            /* last name custom validation messages */
            'last_name' => [
                'last_name.required'        => 'وارد کردن نام خانوادگی الزامی است',
                'last_name.regex'           => 'نام خانوادگی وارد شده نامعتبر است',
                'last_name.min'             => 'حداقل طول نام خانوادگی 2 کاراکتر است',
                'last_name.max'             => 'حداکثر طول نام خانوادگی 255 کاراکتر است'
            ],
            /* parent name custom validation messages */
            'parent_name' => [
                'parent_name.required'  => 'وارد کردن نام پدر الزامی است',
                'parent_name.regex'     => 'مقدار وارد شده نامعتبر است',
                'parent_name.min'       => 'حداقل طول نام پدر 2 کاراکتر است',
                'parent_name.max'       => 'حداکثر طول نام پدر 255 کاراکتر است'
            ],
            /* parent name custom validation messages */
            'birthdate' => [
                'birthdate.required'  => 'وارد کردن تاریخ تولد الزامی است',
                'birthdate.jdate:'     => 'مقدار وارد شده نامعتبر است',
            ],
            /* national code custom validation messages */
            'national_code' => [
                'national_code.required' => 'وارد کردن کد ملی الزامی است',
                'national_code.unique' => 'کد ملی وارد شده قبلا ثبت شده است'
            ],
            /*  email custom validation messages */
//            'email' => [
//                'email.email' => 'ایمیل وارد شده نامعنبر است',
//                'email.unique' => 'ایمیل وارد شده قبلا ثبت شده است'
//            ],
            /* mobile number custom validation messages */
            'mobile_number' => [
                'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
                'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
                'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
                'mobile_number.unique' => 'شماره موبایل وارد شده قبلا ثبت شده است'
            ],
            /*  phone number custom validation messages */
            'phone_number' => [
                'phone_number.required'     => 'وارد کردن شماره تلفن ثابت الزامی است',
                'phone_number.digits'       => 'شماره تلفن وارد شده نامعتبر است',
                'phone_number.starts_with'  => 'شماره تلفن وارد شده نامعتبر است'
            ],
            /*  selfie image custom validation messages */
            'id_img' => [
                'id_img.required'  => 'آپلود کردن تصویر کارت ملی الزامی است',
                'id_img.file'      => 'تصویر آپلود شده نامعتبر است',
                'id_img.mimes'     => 'فرمت تصویر آپلود شده نامعتبر است',
                'id_img.max'       => 'فرمت تصویر آپلود شده نامعتبر است'
            ],
            /*  selfie image custom validation messages */
            'site_img' => [
                'site_img.required'   => 'آپلود کردن تصویر سلفی الزامی است',
                'site_img.file'       => 'تصویر آپلود شده نامعتبر است',
                'site_img.mimes'      => 'فرمت تصویر آپلود شده نامعتبر است',
                'site_img.max'        => 'فرمت تصویر آپلود شده نامعتبر است'
            ],
            /*  site image custom validation messages */
            'selfie_img' => [
                'selfie_img.required' => 'آپلود کردن تصویر مدارک کنار صفحه سایت الزامی است',
                'selfie_img.file'     => 'تصویر آپلود شده نامعتبر است',
                'selfie_img.mimes'    => 'فرمت تصویر آپلود شده نامعتبر است',
                'selfie_img.max'      => 'فرمت تصویر آپلود شده نامعتبر است'
            ],
        ];

        /* fields that are invalid will be saved in this array */
        $invalid_fields = [];

        /* to respond with a proper message  */
        $translation = array(
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'birthdate' => 'تاریخ تولد',
            'parent_name' => 'نام پدر',
            'national_code' => 'کد ملی',
            'mobile_number' => 'شماره موبایل',
            'phone_number' => 'تلفن ثابت',
            'id_img' => 'تصویر کارت ملی',
            'site_img' => 'تصویر مدارک',
            'selfie_img' => 'تصویر سلفی',
        );

        /* fields that have been verified */
        $verified_fields = [];

        /* the fields taht have been updated */
        $updates = [];

        /* if a field dows not exist in user object it's weather an image or user id */
        $other_fields = ['id_img', 'site_img', 'selfie_img'];

        foreach ($request->all() as $key => $value)
        {
            if (array_key_exists($key, $user))
            {
                /* if the field name exists in the db*/
                if ($user[$key] != $value)
                {
                    /* field value has been changed */
                    if ($verification_status[$key] == 2)
                    {
                        /* field has been verified */
                        $verified_fields[$key] = 'فیلد ' . $translation[$key] . ' تأیید شده و امکان تغییر آن وجود ندارد';
                    } else if ($verification_status[$key] == 1) {
                        /* field is being processed  */
                        $verified_fields[$key] = 'فیلد ' . $translation[$key] . ' در انتظار تأیید است و امکان تغییر آن وجود ندارد';
                    } else if ($verification_status[$key] == 0) {
                        /* field has been rejected so it can be validated and saved to the db */
                        $validator = Validator::make([$key => $value], $validation_rules[$key], $validation_messages[$key]);
                        if ($validator->fails())
                        {
                            $invalid_fields[] = [$key => $validator->errors()];
                        }

                        if ($key == 'national_code')
                        {
                            /* check if entered info match with national code */
                            $tmp = (new AuthController())->check_name_similarity($request);
                            if ($tmp['status'] === 'error')
                            {
                                $invalid_fields[] = ['info_mismatch' => $tmp['message']];
                            } else {
                                $request->merge([
                                    'mobile_number' => $user['mobile_number']
                                ]);
                                $tmp = (new AuthController())->match_mobile_id($request);
                                if ($tmp['status'] === 'error')
                                {
                                    $invalid_fields[] = ['mobile_mismatch' => $tmp['message']];
                                } else {
                                    $updates[$key] = $value;
                                }
                            }
                        } else {
                            $updates[$key] = $value;
                        }
                    }
                }
            } else {
                /* these could be images & user_id*/
                if (in_array($key, $other_fields))
                {

                    if ($verification_status[$key] == 2)
                    {
                        /* field has been verified */
                        $verified_fields[$key] = 'فیلد ' . $translation[$key] . ' تأیید شده و امکان تغییر آن وجود ندارد';
                    } else if ($verification_status[$key] == 1) {
                        /* field is being processed  */
                        $verified_fields[$key] = 'فیلد ' . $translation[$key] . ' در انتظار تأیید است و امکان تغییر آن وجود ندارد';
                    } else if ($verification_status[$key] == 0) {
                        $validator = Validator::make([$key => $value], $validation_rules[$key], $validation_messages[$key]);
                        if ($validator->fails())
                        {
                            $invalid_fields[] = [$key => $validator->errors()];
                        }
                        $type = explode('_img', $key)[0];

                        global $docs_path;
                        if (isset($user['docs_path']))
                        {
                            $docs_path = $user['docs_path'];
                        } else {
                            $docs_path = time() . $user['id'];
                            $updates['docs_path'] = $docs_path;
                        }

                        /* save user documents */
                        $doc_types = ['site', 'id', 'selfie'];
                        $path = 'user_docs/' . $docs_path;
                        if (\request()->hasFile($key))
                        {
                            $full_path = \request()->file($type . '_img')->store($path);
                            UserDocument::create([
                                'user_id' => $user['id'],
                                'path' => $full_path,
                                'type' => $type,
                                'status' => 'process',
                            ]);
                        }
                    }
                }
            }
        }
        $tmp = DB::table('users')->find($user['id'])->first();
        $tmp->update($updates);

        if (!empty($invalid_fields) or !empty($verified_fields))
        {
            return ['status' => 'success', 'message' => 'invalid data', 'data' => [
                'verified_fields' => $verified_fields,
                'invalid_fields' => $invalid_fields
            ]];
        } else {
            return ['status' => 'success', 'message' => 'تغییرات با موفقیت اعمال شده و در انتظار تأیید مدیریت می باشد', 'data' => null];
        }
    }

    public function show(Request $request)
    {
        $user = (\request()->user())->toArray();
        $user_docs = (UserDocument::where('user_id', '=', $user['id'])->get())->toArray();

        if ($user_docs)
        {
            $user['documents'] = $user_docs;
        }


        if ($user === null)
        {
            return ['status' => 'error', 'message' => 'کاربری با شناسه وارد شده وجود ندارد', 'data' => null];
        } else {
            return ['status' => 'success', 'message' => 'اطلاعات کاربری', 'data' => $user];
        }
    }

    public function show_image($id, $image)
    {
        $user = User::find($id);
        $path = $user->docs_path . '/' . $image;
        return response()->file(storage_path('app/user_docs/' . $path));
    }

    public function update(Request $request):array
    {
        /*
         * 1. validate fields
         * 2. get user info
         * 3. compare request values and db values
         * 4. if it's changed save the record in FieldHistories else pass
         * */

        $validator = Validator::make($request->all(), [
            'user_id'                   =>'required|numeric',
            'first_name'                => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'last_name'                 => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'parent_name'               => 'required|regex:/^[آابپتثجچحخدذرزژسشصضطظیعغفقئ ق ص ط ق ف کئگلمنوهی ]+$/|min:2|max:255',
            'birthdate'                 => 'required|date',
            'national_code'             => ['required', new IranID],
//            'mobile_number'             => 'required|digits:11|starts_with:09|unique:users,mobile_number',
            'phone_number'              => 'required|digits:11|starts_with:0',
//            'password'                  => 'required|confirmed|string|min:6|max:64',
//            'password_confirmation'     => 'string|min:6|max:64',
            'selfie'                    => 'required|file|mimes:jpg,jpeg,png,bmp|max:20480',
            'id'                        => 'required|file|mimes:jpg,jpeg,png,bmp|max:20480',
            'site'                      => 'required|file|mimes:jpg,jpeg,png,bmp|max:20480',
//            'bill'            => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'email'                     => 'email:rfc,dns|unique:users,email',
//            'video'             => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv|max:1024'
        ], [
            /* user id custom validation messages */
            'user_id.required'          => 'وارد کردن شناسه کاربر الزامی است',
            'user_id.numeric'           => 'شناسه وارد شده نامعتبر است',
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
            /* password custom validation messages */
            'password.required'  => 'وارد کردن رمز عبور الزامی است',
            'password.confirmed'     => 'تأیید رمز عبور الزامی است',
            'password.string'       => 'رمز عبور وارد شده نامعتبر است',
            'password.min'       => 'حداقل طول رمزعبور 6 کاراکتر است',
            'password.max'       => 'حداکثر طول رمزعبور 64 کاراکتر است',
            /* national code custom validation messages */
            'national_code.required' => 'وارد کردن کد ملی الزامی است',
            'national_code.unique' => 'کد ملی وارد شده قبلا ثبت شده است',
            /*  email custom validation messages */
            'email.email' => 'ایمیل وارد شده نامعنبر است',
            'email.unique' => 'ایمیل وارد شده قبلا ثبت شده است',
            /* mobile number custom validation messages */
            'mobile_number.required'    => 'وارد کردن شماره موبایل الزامی است',
            'mobile_number.digits'      => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.starts_with' => 'شماره موبایل وارد شده نامعتبر است',
            'mobile_number.unique' => 'شماره موبایل وارد شده قبلا ثبت شده است',
            /*  phone number custom validation messages */
            'phone_number.required'     => 'وارد کردن شماره تلفن ثابت الزامی است',
            'phone_number.digits'       => 'شماره تلفن وارد شده نامعتبر است',
            'phone_number.starts_with'  => 'شماره تلفن وارد شده نامعتبر است',
            /*  selfie image custom validation messages */
            'id.required'  => 'آپلود کردن تصویر سلفی الزامی است',
            'id.file'      => 'تصویر آپلود شده نامعتبر است',
            'id.mimes'     => 'فرمت تصویر آپلود شده نامعتبر است',
            'id.max'       => 'فرمت تصویر آپلود شده نامعتبر است',
            /*  selfie image custom validation messages */
            'selfie.required'   => 'آپلود کردن تصویر سلفی الزامی است',
            'selfie.file'       => 'تصویر آپلود شده نامعتبر است',
            'selfie.mimes'      => 'فرمت تصویر آپلود شده نامعتبر است',
            'selfie.max'        => 'فرمت تصویر آپلود شده نامعتبر است',
            /*  site image custom validation messages */
            'site.required' => 'آپلود کردن تصویر مدارک کنار صفحه سایت الزامی است',
            'site.file'     => 'تصویر آپلود شده نامعتبر است',
            'site.mimes'    => 'فرمت تصویر آپلود شده نامعتبر است',
            'site.max'      => 'فرمت تصویر آپلود شده نامعتبر است',
            /*  video custom validation messages */
//            'video.required'    => 'آپلود کردن ویدئو است',
//            'video.file'        => 'تصویر آپلود شده نامعتبر است',
//            'video.mimetypes'   => 'فرمت ویدیو آپلود شده نامعتبر است',
//            'video.max'         => 'فرمت ویدیو آپلود شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        /*
         * get user as an array
         * save the changes
         * update the user using the new array
         * */
        /* get uploaded file changes */
        $user = User::find(\request('user_id'))->toArray();
        $new_values = $request->toArray();
        if ($user === null)
        {
            return ['status' => 'error', 'message' => 'کاربری با شناسه وارد شده وجود ندارد', 'data' => null];
        } else {
            $changed = array();
            foreach ($request->all() as $key => $value)
            {
                if ($new_values[$key] !== $user[$key])
                {
                    $changed[] = $key;
                }
            }
            return ['status' => 'success', 'message' => 'اطلاعات کاربری با موفقیت به روز رسانی شدند', 'data' => null];
        }


    }

    public function destroy(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ], [
            'user_id.required' => 'وارد کردن شناسه کاربر الزامی است',
            'user_id.numeric' => 'شناسه وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = DB::table('users')->find(\request('user_id'));
        if ($user === null)
        {
            return ['status' => 'error', 'message' => 'کاربری با شناسه وارد شده وجود ندارد', 'data' => null];
        } else {
            $tmp = DB::table('users')->delete(\request('user_id'));
            if ($tmp === 1)
            {
                return ['status' => 'success', 'message' => 'کاربر موردنظر با موفقیت حذف شد', 'data' => null];
            } else {
                return ['status' => 'error', 'message' => 'حذف کاربر ناموفق بود', 'data' => null];
            }
        }
    }

    public function change_status(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'status' => 'required|in:0,1', /* 0 or 1*/
        ], [
            'user_id.required' => 'وارد کردن شناسه کاربر الزامی است',
            'user_id.numeric' => 'شناسه وارد شده نامعتبر است',
            'status.required' => 'وارد کردن وضعیت کاربر الزامی است',
            'status.in' => 'وضعیت وارد شده نامعتبر است',
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = DB::table('users')->find(\request('user_id'));
        if ($user === null)
        {
            return ['status' => 'error', 'message' => 'کاربری با شناسه وارد شده وجود ندارد', 'data' => null];
        } else {
            $tmp = DB::table('users')->where('id', '=', \request('user_id'))->update(['status' => \request('status')]);
            if ($tmp === 1)
            {
                return ['status' => 'success', 'message' => 'وضعیت کاربر با موفقیت تغییر کرد', 'data' => null];
            } else {
                return ['status' => 'error', 'message' => 'مشکلی در تغییر وضعیت کاربر به وجود آمد', 'data' => null];
            }
        }
    }

    public function change_user_verification_status(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'is_verified' => 'required|in:0,1,2', /* 0 or 1*/
        ], [
            'user_id.required' => 'وارد کردن شناسه کاربر الزامی است',
            'user_id.numeric' => 'شناسه وارد شده نامعتبر است',
            'is_verified.required' => 'وارد کردن وضعیت احراز هویت کاربر الزامی است',
            'is_verified.in' => 'مقدار وارد شده نامعتبر است',
        ]);


        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }

        $user = DB::table('users')->find(\request('user_id'));
        if ($user === null)
        {
            return ['status' => 'error', 'message' => 'کاربری با شناسه وارد شده وجود ندارد', 'data' => null];
        } else {
            switch (intval(\request('is_verified')))
            {
                case (0):
                    \request()->merge(['is_verified' => "banned"]); break;
                case (1):
                    \request()->merge(['is_verified' => "process"]); break;
                case (2):
                    \request()->merge(['is_verified' => "verified"]); break;
            }
            if ($user->is_verified === \request('is_verified'))
            {
                return ['status' => 'error', 'message' => 'وضعیت فعلی کاربر با وضعیت وارد شده یکی است', 'data' => null];
            } else {
                $tmp = DB::table('users')->where('id', '=', \request('user_id'))->update(['is_verified' => \request('is_verified')]);
                if ($tmp === 1)
                {
                    return ['status' => 'success', 'message' => 'وضعیت کاربر با موفقیت تغییر کرد', 'data' => null];
                } else {
                    return ['status' => 'error', 'message' => 'مشکلی در تغییر وضعیت کاربر به وجود آمد', 'data' => null];
                }
            }
        }
    }

    public function update_documents()
    {
        /*
         * check if the user has a file of requested type which is verified?
         * if yes it can not be changed
         * if no validate it and save it then
         * */
    }

    public function download_path(Request $request)
    {
        $validator = Validator::make($request->all(), [

        ], [

        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields', 'data' => $validator->errors()];
        }
    }

    public function change_field_verification_status(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'field_name' => 'required|string',
            'status' => 'required|in:0,1'
        ], [
            'user_id.required' => 'وارد کردن شناسه کاربری الزامی است',
            'user_id.exists' => 'شناسه وارد شده نامعتبر است',
            'field_name.required' => 'وارد کردن نام فیلد اجباری است',
            'field_name.string' => 'نام وارد شده برای فیلد نامعتبر است',
            'status.required' => 'وارد کردن وضعیت فیلد اجباری است',
            'status.in' => 'مقدار وارد شده نامعتبر است'
        ]);

        if ($validator->fails())
        {
            return ['status' => 'error', 'message' => 'invalid fields' , 'data' => $validator->errors()];
        }

        $user = User::find(\request('user_id'))->first();
        $field_verification = $user->field_verification;

        if ($field_verification)
        {
            $field = \request('field_name');
            $field_verification = json_decode($field_verification, true);
            if (array_key_exists($field, $field_verification))
            {
                $field_verification[$field] = intval(\request('status'));
                $user->field_verification = json_encode($field_verification);
                $user->save();
                return ['status' => 'success', 'message' => 'تغییر وضعیت با موفقیت انجام شد' , 'data' => null];
            } else {
                return ['status' => 'error', 'message' => 'فیلدی با نام داده شده موجود نمی باشد' , 'data' => null];
            }
        } else {
            return ['status' => 'error', 'message' => 'invalid fields' , 'data' => null];
        }

    }
}
