<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighRiskResponse extends Model
{
    use HasFactory;

    protected $table = 'high_risk_response';
    protected $casts = [
        'option_ids' => 'array',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}