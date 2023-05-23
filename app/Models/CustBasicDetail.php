<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustBasicDetail extends Model
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

    public function cities()
    {
        return $this->hasOne(City::class, 'id', 'city');
    }
    public function banks()
    {
        return $this->hasOne(Bank::class, 'id', 'bank');
    }
    public function occupations()
    {
        return $this->hasOne(Occupation::class, 'id', 'occupation');
    }
    public function countries()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }
    public function income_sources()
    {
        return $this->hasOne(SourcesofIncome::class, 'id', 'income_source');
    }

  
    

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
