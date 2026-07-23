<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'content',
        'program_id',
        'product_code',
        'show_in_menu',
        'menu_order',
        'status',
    ];

    public function pageable()
    {
        return $this->morphTo();
    }

    public function program()
    {
        return $this->belongsTo(Programs::class, 'program_id');
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

        static::deleting(function ($page) {
            if (!$page->isForceDeleting()) {
                $page->slug = $page->slug . '-deleted-' . time();
                $page->saveQuietly();
            }
        });
    }
}
