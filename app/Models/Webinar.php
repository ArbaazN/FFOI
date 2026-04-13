<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Webinar extends Model
{
    use HasFactory;

    protected $table = 'webinar';

    protected $fillable = [
        'webinar_type',
        'banner_image',
        'slug',
        'title',
        'subtitle',
        'short_desc',
        'desc',
        'image',
        'perfect_for_desc',
        'perfect_for_desclaimer',
        'works_desc',
        'why_ffoi_heading',
        'why_ffoi_desc',
        'faqs_question',
        'faqs_answer',
        'best_of_industries_heading',
        'name_new',
        'Designation_new',
        'Description_new',
        'Areaofexperties_new',
        'linkedIn_new',
        'image_new',
        'logo_image1_new',
        'logo_image2_new',
        'final_CTA_desc',
    ];

    /**
     * Auto-generate slug from title if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function upcomingSessions()
    {
        return $this->hasMany(WebinarUpcomingSession::class, 'webinar_id');
    }
}
