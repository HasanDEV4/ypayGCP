<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
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
    public function getParsedStatusAttribute()
    {
        if(@$this->attributes['status'] == 1) {
            return 'Active';
        } else if(@$this->attributes['status'] == 0) {
            return 'In-Active';
        }
        return '';
    }

    public function amc()
    {
        return $this->hasOne(Amc::class, 'id', 'amc_id');
    }
    public function risk_profile()
    {
        return $this->hasOne(RiskProfile::class, 'id', 'risk_profile_id');
    }
    public function conversions()
    {
        return $this->hasMany(Conversion::class, 'fund_id', 'id');
    }
    public function additional_details()
    {
        return $this->hasOne(FundsAdditionalDetail::class);
    }

    public function asset()
    {
        return $this->hasMany(FundAsset::class);
    }

    public function asset_allocations() {
        return $this->hasMany(FundAssetAllocation::class);
    }

    public function holdings() {
        return $this->hasMany(FundHolding::class);
    }

    public function fund_bank() {
        return $this->hasOne(FundsBankDetail::class);
    }

    public function investments(){
        return $this->hasMany(Investment::class,'fund_id','id');
    }
    public function amcCustProfile()
    {
        return $this->hasMany(AmcCustProfile::class,'amc_id','id');
    }

    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'fund_id', 'id');
    }

    protected $appends = ['parsedStatus'];
}
