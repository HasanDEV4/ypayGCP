<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    use HasFactory;

    protected $table = 'conversions';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'approved_date' => 'datetime:Y-m-d h:i:s a',
    ];
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function fund() {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }
    public function investment(){
        return $this->hasOne(Investment::class, 'id', 'investment_id');
    }
    public function redemption(){
        return $this->hasMany(Redemption::class, 'conversion_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(Conversion::class, 'conversion_id');
    }

    public function parent()
    {
        return $this->belongsTo(Conversion::class, 'conversion_id');
    }
}