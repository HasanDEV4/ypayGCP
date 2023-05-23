<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPCount extends Model
{
    use HasFactory;

    protected $table = 'send_otp_count';
}