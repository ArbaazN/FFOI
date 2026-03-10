<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blogs';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'title',
        'subtitle',
        'author',
        'publish_date',
        'category_id',
        'feature_content',
        'status',
        'images',
        'mobile_image',
        'slug',
        'content',
        'author_image',
        'author_desc',
        'faqs_question',
        'faqs_answer',
        'fb_url',
        'twitter_url',
        'insta_url',
        'linkedIn_url',
        'yt_url'
    ];

    protected $casts = [
        'publish_date' => 'datetime:Y-m-d',
        'feature_content' => 'boolean',
        'status' => 'boolean',
    ];

    public function getContentAttribute($value)
    {
        return html_entity_decode($value);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    protected $appends = ['image_url','mobile_image_url','author_image_url', 'decoded_content'];
    
    public function getImageUrlAttribute()
    {
        return $this->images ? asset('storage/' . $this->images) : null;
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->mobile_image ? asset('storage/' . $this->mobile_image) : null;
    }

    public function getAuthorImageUrlAttribute()
    {
        return $this->author_image ? asset('storage/' . $this->author_image) : null;
    }

    // return decoded HTML content
    public function getDecodedContentAttribute()
    {
        return html_entity_decode($this->content);
    }

    // Auto-fill created_by & updated_by
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
