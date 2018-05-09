<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
	use Notifiable;
 
    protected $guard = 'admin';
 
    protected $fillable = [
        'name', 'profile', 'mobile','address','location','about_me','email', 'password',
    ];

}
