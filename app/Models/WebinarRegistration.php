<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebinarRegistration extends Model
{
    use HasFactory;

    protected $table = 'webinar_registration';

    protected $fillable = [
        'webinar_id',
        'session_id',
        'name',
        'email',
        'contact',
        'state',
        'city',
        'highest_qualification',
        'current_status',
        'topic_interested_in',
        'message',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class, 'webinar_id');
    }

    public function session()
    {
        return $this->belongsTo(WebinarUpcomingSession::class, 'session_id');
    }
}
