<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcCountry extends Model
{
    use HasFactory;

    protected $table = 'amc_countries';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'ypay_country_id', 'id');
    }
}