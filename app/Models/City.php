<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function CustBasicDetail()
    {
        return $this->belongsTo(CustBasicDetail::class);
    }
    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
}
