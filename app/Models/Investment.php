<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
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

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function fund() {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }
    public function conversions()
    {
        return $this->hasMany(Conversion::class, 'investment_id', 'id');
    }
    public function amc_fund() {
        return $this->hasOne(AmcFund::class, 'id', 'amc_fund_id');
    }
    public function redemption(){
        return $this->hasMany(Redemption::class, 'invest_id', 'id');
    }
   

    protected $appends = ['parsedStatus'];
}
