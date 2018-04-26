<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
	protected $fillable = ['feed_id','attachment','attachment_type'];

}
