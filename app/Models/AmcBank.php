<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcBank extends Model
{
    use HasFactory;

    protected $table = 'amc_banks';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'ypay_bank_id','id');
    }
}