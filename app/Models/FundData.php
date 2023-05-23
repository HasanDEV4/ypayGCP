<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundData extends Model
{
    use HasFactory;

    protected $table = 'fund_scrapped_data';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'created_at' => 'datetime:Y-m-d h:i:s a',
  ];
  public function ypay_fund()
    {
        return $this->belongsTo(Fund::class, 'ypay_fund_id','id');
    }
}
