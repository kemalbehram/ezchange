<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankCardController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPaymentController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('show-image/{id}/{image}', [UserController::class, 'show_image']);

/* Config Routes */
Route::post('all-configs', [ConfigurationController::class, 'index'])->middleware('auth:api')->name('configs');
Route::post('new-config', [ConfigurationController::class, 'store'])->middleware('auth:api');
Route::post('delete-config', [ConfigurationController::class, 'destroy'])->middleware('auth:api');
Route::post('update-config', [ConfigurationController::class, 'update'])->middleware('auth:api');

/* SMS Routes */
Route::post('verification-rejected', [SMSController::class, 'verification_rejected'])->middleware('auth:api');
Route::post('verification-accepted', [SMSController::class, 'verification_accepted'])->middleware('auth:api');
Route::post('send-verification-code', [SMSController::class, 'send_verification_code'])->middleware('auth:api');
Route::post('read-verification-code', [SMSController::class, 'read_verification_code'])->middleware('auth:api');

/* Authentication Routes */
Route::post('login', [AuthController::class, 'login']);
Route::post('update-password', [AuthController::class, 'update_password'])->middleware('auth:api');
Route::post('reset-password', [AuthController::class, 'reset_password']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('verify-reset-code', [AuthController::class, 'verify_reset_code'])->middleware('auth:api');
Route::post('verify-mobile-code', [AuthController::class, 'verify_mobile_code'])->middleware('auth:api');
Route::post('match-mobile', [AuthController::class, 'match_mobile_id'])->middleware('auth:api');
Route::post('check-name-similarity', [AuthController::class, 'check_name_similarity'])->middleware('auth:api');


/* User Routes */
Route::post('register', [UserController::class, 'store']);
Route::post('update-profile', [UserController::class, 'update_profile'])->middleware('auth:api');
Route::post('get-users', [UserController::class, 'index'])->middleware('auth:api');
Route::post('get-user', [UserController::class, 'show'])->middleware('auth:api');
Route::post('update-user', [UserController::class, 'update'])->middleware('auth:api');
Route::post('change-status', [UserController::class, 'change_status'])->middleware('auth:api');
Route::post('change-user-verification-status', [UserController::class, 'change_user_verification_status'])->middleware('auth:api');
Route::post('delete-user', [UserController::class, 'destroy'])->middleware('auth:api');
Route::post('change-field-verification-status', [UserController::class, 'change_field_verification_status'])->middleware('auth:api');


/* Bank Card Routes */
Route::post('add-card', [BankCardController::class, 'store'])->middleware('auth:api');
Route::post('delete-card', [BankCardController::class, 'destroy'])->middleware('auth:api');
Route::post('get-card-iban', [BankCardController::class, 'get_card_iban'])->middleware('auth:api');
Route::post('get-iban-info', [BankCardController::class, 'get_iban_info'])->middleware('auth:api');
Route::post('get-card-info', [BankCardController::class, 'get_card_info'])->middleware('auth:api');
Route::post('get-user-cards', [BankCardController::class, 'get_user_cards'])->middleware('auth:api');
Route::post('get-all-cards', [BankCardController::class, 'index'])->middleware('auth:api');

/* Order Routes */
Route::post('all-orders', [OrderController::class, 'index'])->middleware('auth:api');
Route::post('new-order', [OrderController::class, 'store'])->middleware('auth:api');
Route::post('get-user-wallet', [OrderController::class, 'get_user_wallet'])->middleware('auth:api');
Route::post('update-order', [OrderController::class, 'update'])->middleware('auth:api');
Route::post('delete-order', [OrderController::class, 'destroy'])->middleware('auth:api');
Route::post('order-total', [OrderController::class, 'calculate_order_total'])->middleware('auth:api');
Route::post('current-price', [OrderController::class, 'tether_current_price'])->middleware('auth:api');

/* User Payment Routes */
Route::post('all-user-payments', [UserPaymentController::class, 'index'])->middleware('auth:api');
Route::post('pay-with-jibit', [UserPaymentController::class, 'pay_with_jibit'])->middleware('auth:api');
Route::post('get-user-payment', [UserPaymentController::class, 'show'])->middleware('auth:api');
Route::post('delete-user-payments', [UserPaymentController::class, 'destroy'])->middleware('auth:api');
Route::post('delete-user-payments', [UserPaymentController::class, 'destroy'])->middleware('auth:api');

// send order info to service and get order identifier
Route::post('send-order-request-to-jibit', [OrderController::class, 'send_order_request_to_jibit'])->middleware('auth:api');
Route::post('check-payment-status', [UserPaymentController::class, 'check_payment_status'])->name('check-payment-status');

// get payment status after payment is successful or not.
// This is also the redirect url
//Route::post('get-payment-status', [UserPaymentController::class, 'get_payment_status']);


// Inquiry the order

/* wallet routes */
Route::post('all-wallets', [WalletController::class, 'index']);
Route::post('new-wallet', [WalletController::class, 'store']);
Route::post('select-random-wallet', [WalletController::class, 'select_random_wallet']);
Route::post('new-wallet', [WalletController::class, 'store']);
Route::post('new-wallet', [WalletController::class, 'store']);
/* wallet address from crm */
Route::post('get-wallets', [WalletController::class, 'get_wallets_data']);

Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized-user');
