<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = array(
        'amount_in_tethers', 'user_id', 'amount_in_rials', 'price_in_rials',
        'type', 'from_wallet_id', 'to_wallet_id', 'from_wallet', 'to_wallet',
        'status', 'tx_id', 'payment_status', 'pay_time', 'bin_id', 'bin_status',
        'bin_tx_id', 'bank_card_id'
    );
}
