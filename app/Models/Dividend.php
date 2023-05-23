<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{
    use HasFactory;

    protected $table = 'dividends';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'distribution_date'=>'datetime:Y-m-d',
    ];
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function fund() {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }
    public function redemption(){
        return $this->hasMany(Redemption::class, 'conversion_id', 'id');
    }
}