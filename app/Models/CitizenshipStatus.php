<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;

class CitizenshipStatus extends Model
{
    use HasFactory;

    protected $table = 'citizenship_status';
}
