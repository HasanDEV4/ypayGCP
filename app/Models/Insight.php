<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insight extends Model
{
    use HasFactory;



    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'created_at' => 'datetime:Y-m-d h:i:s a',
  ];
  
    

        /**
     * parsed status
     *
     * @return string
     */

    public function insight_category() {
        return $this->hasOne(InsightCategory::class, 'id', 'category_id');
    }

    public function insight_tag() {
        return $this->hasOne(InsightTag::class, 'id', 'tag_id');
    }
}
