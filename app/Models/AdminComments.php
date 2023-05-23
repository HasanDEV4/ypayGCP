<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminComments extends Model
{
    use HasFactory;

    protected $table = 'admin_comments_for_users';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function commented_by()
    {
        return $this->belongsTo(User::class, 'comment_by','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}