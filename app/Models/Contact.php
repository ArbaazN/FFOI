<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use HasFactory, Notifiable;

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
    ];
}
