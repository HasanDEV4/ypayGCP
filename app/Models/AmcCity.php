<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcCity extends Model
{
    use HasFactory;

    protected $table = 'amc_cities';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'ypay_city_id','id');
    }
}