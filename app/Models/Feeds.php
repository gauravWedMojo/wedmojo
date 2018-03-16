<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    protected $table = 'Feeds';
    protected $fillable = ['wedding_id','user_id','attachment','attachment_type'];
}
