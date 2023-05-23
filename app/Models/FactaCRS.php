<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactaCRS extends Model
{
    use HasFactory;

    protected $table = 'facta_crs';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
