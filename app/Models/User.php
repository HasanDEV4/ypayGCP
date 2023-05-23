<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /*Soft Deletes Eloquent is removed purposely to delete users for testing purpose (This change should only be 
    on staging and test environment it should not be deployed on production)*/
    // use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function user_otp() {
        return $this->hasOne(Otp::class);
    }
    public function cust_basic_detail()
    {
        return $this->hasOne(CustBasicDetail::class, 'user_id', 'id');
    }
    public function cust_cnic_detail()
    {
        return $this->hasOne(CustCnicDetail::class,'user_id', 'id');
    }
    public function cust_bank_detail()
    {
        return $this->hasOne(CustBankDetail::class, 'user_id', 'id');
    }
    public function cust_account_detail()
    {
        return $this->hasOne(CustAccountDetail::class, 'user_id', 'id');
    }
    public function image()
    {
        return $this->hasOne(UserImage::class, 'user_id', 'id');
    }
    public function cust_goal()
    {
        return $this->hasMany(CustomerGoal::class, 'user_id', 'id');
    }
    public function cust_investment()
    {
        return $this->hasMany(Investment::class, 'user_id', 'id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id')->orderBy('id', 'desc')->latest()->take(20);

    }
    public function comments()
    {
        return $this->hasMany(AdminComments::class, 'user_id', 'id')->orderBy('id', 'desc')->latest()->take(20);
    }
    public function amcCustProfile()
    {
        return $this->hasMany(AmcCustProfile::class, 'user_id', 'id')->orderBy('id', 'desc')->latest()->take(20);;
    }
    public function amcCustProfiles()
    {
        return $this->hasMany(AmcCustProfile::class, 'user_id', 'id');
    }

    public function amc()
    {
        return $this->hasMany(Amc::class);
    }

    public function facta_crs()
    {
        return $this->hasMany(FactaCRS::class, 'user_id', 'id');
    }
    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'user_id', 'id');
    }
    public function change_request()
    {
        return $this->hasMany(ChangeRequest::class, 'user_id', 'id');
    }
   

  
}
