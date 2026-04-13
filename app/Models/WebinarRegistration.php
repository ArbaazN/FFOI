<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Webinar;

class WebinarRegistration extends Model
{
    use HasFactory;

    protected $table = 'webinar_registration';

    protected $fillable = [
        'webinar_id',
        'name',
        'email',
        'contact',
        'city',
        'highest_qualification',
        'current_status',
        'topic_interested_in',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class, 'webinar_id');
    }
}
