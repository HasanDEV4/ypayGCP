<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcesofIncome extends Model
{
    use HasFactory;

    protected $table = 'source_of_incomes';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->belongsTo(Amc::class, 'amc_id','id');
    }
}