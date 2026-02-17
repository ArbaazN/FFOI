<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Contact extends Model
{
    use HasFactory,HasRoles, Notifiable;

    protected $table = 'contacts';

    protected $fillable = [
        'fullname', 
        'email', 
        'contact', 
        'state', 
        'city', 
        'who_i_am', 
        'area_of_interest', 
        'message', 
        'created_at', 
        'updated_at'
    ];
}
