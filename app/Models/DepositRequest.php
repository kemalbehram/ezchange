<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositRequest extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transaction()
    {
        $this->belongsTo(Transaction::class, 'tx_id', 'id');
    }
}
