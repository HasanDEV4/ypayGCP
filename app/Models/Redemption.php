<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;



    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'verified_at' => 'datetime:Y-m-d h:i:s a',
      'updated_at' => 'datetime:Y-m-d',
      'approved_date' => 'datetime:Y-m-d\TH:i',
  ];
  
    

        /**
     * parsed status
     *
     * @return string
     */
    public function getParsedStatusAttribute()
    {
        if($this->attributes['status'] == 1) {
            return 'Active';
        } else if($this->attributes['status'] == 0) {
            return 'In-Active';
        } 
        return '';
    }
    public function investment(){
        return $this->hasOne(Investment::class, 'id', 'invest_id');
    }

    public function conversion(){
        return $this->hasOne(Conversion::class, 'id', 'conversion_id');
    }

    public function dividend(){
        return $this->hasOne(Dividend::class, 'id', 'dividend_id');
    }

    protected $appends = ['parsedStatus'];
}
