<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function category()
    {
        return $this->hasOne(QuestionCategory::class, 'id', 'cat_id');
    }
    public function option()
    {
        return $this->hasMany(Options::class);
    }
}