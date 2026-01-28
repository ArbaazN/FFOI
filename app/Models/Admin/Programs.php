<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Programs extends Model
{
    use SoftDeletes;

    protected $table = 'programs';
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
    ];
    public $timestamps = true;
    //
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function pages()
    {
        return $this->morphMany(Page::class, 'pageable');
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