<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiddenFeed extends Model
{
	protected $table = "hidden_feeds";
	protected $fillable = ['feed_id','user_id'];
}
