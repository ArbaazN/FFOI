<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MembershipBenefit extends Model
{
    use HasFactory;

    protected $table = 'membership_benefit';
    protected $guarded = [];
}
