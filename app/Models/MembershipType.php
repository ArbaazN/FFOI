<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MembershipType extends Model
{
    use HasFactory;

    protected $table = 'membership_type';
    protected $guarded = [];
}
