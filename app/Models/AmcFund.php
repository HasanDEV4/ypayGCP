<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcFund extends Model
{
    use HasFactory;

    protected $table = 'amc_funds';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
    public function fund()
    {
        return $this->belongsTo(Fund::class, 'ypay_fund_id', 'id');
    }
}