<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedReport extends Model
{
   protected $table = 'report_feeds';
   protected $fillable = ['user_id','feed_id','message'];
}
