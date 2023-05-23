<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcSourceofIncome extends Model
{
    use HasFactory;

    protected $table = 'amc_sources_income';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
    public function incomesources()
    {
        return $this->belongsTo(SourcesofIncome::class, 'ypay_source_of_income_id','id');
    }
}