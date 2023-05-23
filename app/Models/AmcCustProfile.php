<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcCustProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'verified_at' => 'datetime:Y-m-d h:i:s a',
    ];
    public function accounttype()
    {
        return $this->hasOne(AccountType::class, 'id', 'account_type');
    }

    public function amc()
    {
        return $this->belongsTo(Amc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
