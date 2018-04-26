<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $fillable = ['contact_name','contact_number','user_id'];
}
