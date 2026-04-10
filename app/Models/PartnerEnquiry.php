<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerEnquiry extends Model
{
    use HasFactory;

    protected $table = 'partner_enquiries';

    protected $fillable = [
        'fullname',
        'contact',
        'email',
        'preferred_territory',
        'city',
        'current_occupation_business',
        'partner_reason',
        'consent',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];
}
