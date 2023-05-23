<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustCnicDetail extends Model
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
    // public function getParsedStatusAttribute()
    // {
    //     if($this->attributes['status'] == 1) {
    //         return 'Active';
    //     } else if($this->attributes['status'] == 0) {
    //         return 'In-Active';
    //     } 
    //     return '';
    // }
    public function citizenshipstatus()
    {
        return $this->hasOne(CitizenshipStatus::class, 'id', 'citizenship_status');
    }
    // protected $appends = ['parsedStatus'];
}
