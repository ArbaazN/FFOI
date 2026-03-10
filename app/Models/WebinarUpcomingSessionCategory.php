<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebinarUpcomingSessionCategory extends Model
{
    use HasFactory;

    protected $table = 'webinar_upcoming_session_category';

    protected $fillable = [
        'image',
        'slug',
        'session_name',
        'heading',
        'short_desc',
    ];

    public function session()
    {
        return $this->hasMany(WebinarUpcomingSession::class, 'session_id', 'id');
    }
}
