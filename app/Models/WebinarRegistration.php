<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class WebinarRegistration extends Model
{
    use HasFactory;

    protected $table = 'webinar_registration';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'city',
        'highest_qualification',
        'current_status',
        'topic_interested_in',
    ];
}
