<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SMS extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sms';
    protected $fillable = ['template', 'messageid', 'message', 'status', 'statustext', 'sender', 'receptor', 'date', 'cost'];
}
