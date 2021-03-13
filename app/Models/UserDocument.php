<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class UserDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'path', 'type', 'status'];
}
