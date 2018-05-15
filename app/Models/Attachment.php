<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Attachment extends Model
{
	protected $fillable = ['feed_id','attachment','attachment_type'];

	public function getCreatedAtAttribute($value){
		// dd(Carbon::parse($value)->timezone('Asia/Calcutta')->format('d-M-Y h:i:s a'));
		return Carbon::parse($value)->timezone('Asia/Calcutta')->format('Y-m-d h:i:s a');
	}

	public function getUpdatedAtAttribute($value){
		return Carbon::parse($value)->timezone('Asia/Calcutta')->format('Y-m-d h:i:s a');
	}

}
