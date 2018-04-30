<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    protected $table = 'Feeds';
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
}
