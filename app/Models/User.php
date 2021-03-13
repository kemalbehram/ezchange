<?php namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentTaggable\Taggable;
use Str;

class User extends EloquentUser
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'users';

    /**
     * The attributes to be fillable from the model.
     *
     * A dirty hack to allow fields to be fillable by calling empty fillable array
     *
     * @var array
     */
    use Taggable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'reset_pass_hash',
        'status', 'is_verified', 'birthdate', 'mobile_is_verified',
        'parent_name', 'national_code', 'email', 'mobile_number',
        'phone_number', 'referral_code', 'docs_path', 'email_verified_at',
        'password', 'last_login_date', 'last_login_ip', 'api_token', 'field_verification'
    ];
    protected $guarded = ['id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
//        'docs_path',
        'api_token',
        'reset_pass_hash',
        'mobile_verification_hash',
        'phone_verification_hash',
        'deleted_at',
    ];

    /**
    * To allow soft deletes
    */
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = ['full_name'];
    public function getFullNameAttribute()
    {
        return Str::limit($this->first_name . ' ' . $this->last_name, 30);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
	protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bank_cards()
    {
        $this->hasMany(BankCard::class, 'user_id', 'id');
    }
}
