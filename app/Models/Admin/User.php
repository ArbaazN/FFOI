<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory,HasRoles,SoftDeletes;
    protected $guard_name = 'web'; 
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'created_at', 'updated_at', 'email_verified_at'
    ];
    public $timestamps = true;
    protected $hidden = [
        'password', 'remember_token',
    ];

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
