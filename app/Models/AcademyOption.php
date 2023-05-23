<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademyOption extends Model
{
    use HasFactory;

    protected $table = 'ac_quest_options';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function question()
    {
        return $this->belongsTo(ChapterQuestions::class, 'question_id','id');
    }
}