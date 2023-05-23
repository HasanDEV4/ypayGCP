<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmcDataLog extends Model
{
    use HasFactory;

    protected $table = 'amc_apis_url_log';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function amc()
    {
        return $this->hasOne(Amc::class, 'id', 'amc_id');
    }
    public function user_id()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}