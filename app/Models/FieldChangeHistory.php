<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldChangeHistory extends Model
{
    use HasFactory;

    protected $fillable = array('user_id', 'changed_by', 'field_name', 'changed_from', 'changed_to');
}
