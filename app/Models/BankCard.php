<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCard extends Model
{
    use HasFactory;
    protected $fillable = array('status', 'is_verified', 'user_id', 'owner_first_name', 'owner_last_name', 'bank', 'account_number', 'card_number', 'iban', 'deposit', 'withdraw');
    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bank()
    {
        $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
