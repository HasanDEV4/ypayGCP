<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterQuestions extends Model
{
    use HasFactory;

    protected $table = 'chapter_questions';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function chapter()
    {
        return $this->belongsTo(AcademyChapter::class, 'chapter_id','id');
    }
}