<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequestStatus extends Model
{
    protected $table = 'change_request_status';
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
    public function amc()
    {
        return $this->hasOne(Amc::class, 'id', 'amc_id');
    }
    public function change_request()
    {
        return $this->hasOne(ChangeRequest::class, 'id', 'change_request_id');
    }
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
