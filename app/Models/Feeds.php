<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feeds extends Model
{
    protected $table = 'feeds';
    protected $fillable = ['wedding_id','user_id',/*'attachment','attachment_type',*/'caption'];

    public function feed_created_by_user_detail(){
    	// return $this->hasOne(\App\Models\User::class,'id','user_id');
    	return $this->belongsTo(\App\Models\User::class,'user_id','id');
    }

	public function wedding(){
		return $this->hasOne(\App\Models\Wedding::class,'id','wedding_id');
	}

	public function attachment(){
		return $this->hasMany(\App\Models\Attachment::class,'feed_id','id');
	}

	public function getCreatedAtAttribute($value){
		// dd(Carbon::parse($value)->timezone('Asia/Calcutta')->format('d-M-Y h:i:s a'));
		return Carbon::parse($value)->timezone('Asia/Calcutta')->format('Y-m-d h:i:s a');
	}

	public function getUpdatedAtAttribute($value){
		return Carbon::parse($value)->timezone('Asia/Calcutta')->format('Y-m-d h:i:s a');
	}
}
