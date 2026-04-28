<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class WebinarUpcomingSession extends Model
{
    use HasFactory;

    protected $table = 'webinar_upcoming_session';

    protected $fillable = [
        'banner_image',
        'slug',
        'session_id',
        'topic_name',
        'title',
        'subtitle',
        'date',
        'time',
        'time_from',
        'mode',
        'by',
        'image',
        'why_attend_section_heading',
        'why_attend_section_points',
        'why_learn_heading',
        'why_learn_points',
        'who_attend_heading',
        'who_attend_points',
        'who_attend_disclaimer',
        'career_role_heading',
        'career_role_points',
        'career_role_disclaimer',
        'image_attend',
        'how_session_help_heading',
        'how_session_help_points',
        'how_session_help_disclaimer',
        'learn_with_ffoi_heading',
        'learn_with_ffoi_points',
        'faqs_question',
        'faqs_answer',
        'final_CTA_desc',
        'instructor_image',
        'instructor_name',
        'instructor_designation',
        'instructor_experience',
        'instructor_desc',
        'instructor_logo_image1',
        'instructor_logo_image2',
        'best_of_industries_heading',
        'name_new',
        'Designation_new',
        'Description_new',
        'Areaofexperties_new',
        'linkedIn_new',
        'image_new',
        'logo_image1_new',
        'logo_image2_new',

    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function category()
    {
        // return $this->belongsTo(WebinarUpcomingSessionCategory::class, 'session_id');
        return $this->belongsTo(WebinarUpcomingSessionCategory::class, 'session_id', 'id');
    }

    
}
