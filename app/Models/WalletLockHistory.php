<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletLockHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'wallet_lock_histories';
    protected $fillable = array();
}
