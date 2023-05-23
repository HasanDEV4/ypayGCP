<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amc extends Model
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
     * Get the  .
     */
    public function amcCustProfile()
    {
        return $this->hasMany(AmcCustProfile::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    

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

    public function getLogoLinkAttribute() {
        return @$this->attributes['logo'] ? asset($this->attributes['logo']) : @$this->attributes['logo'];
    }

    protected $appends = ['parsedStatus', 'logo_link'];
}
