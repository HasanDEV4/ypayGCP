<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;

class UserImage extends Model
{
    use HasFactory;

    protected $table = 'users_images';
}
