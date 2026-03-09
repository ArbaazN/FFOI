<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MemberShip extends Model
{
    use HasFactory;

    protected $table = 'membership';
    protected $guarded = [];
}
