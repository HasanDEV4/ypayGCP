<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskProfile extends Model
{
    use HasFactory;

    protected $table = 'risk_profiles';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function fund()
    {
        return $this->hasMany(Fund::class);
    }
}