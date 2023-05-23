<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;



    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'updated_at' => 'datetime:Y-m-d',
  ];
  
    

        /**
     * parsed status
     *
     * @return string
     */
    // public function getParsedStatusAttribute()
    // {
    //     if($this->attributes['status'] == 1) {
    //         return 'Active';
    //     } else if($this->attributes['status'] == 0) {
    //         return 'In-Active';
    //     } 
    //     return '';
    // }

    // protected $appends = ['parsedStatus'];
}
