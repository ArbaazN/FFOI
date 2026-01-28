<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'file_name',
        'file_path',
        'file_url',
        'mime_type',
        'type',
        'size',
        'page_id',
        'field',
        'uploaded_by',
    ];

    // Relationship: the user who uploaded
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Optional: media belongs to a page
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->uploaded_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->uploaded_by = Auth::id();
            }
        });
    }
}