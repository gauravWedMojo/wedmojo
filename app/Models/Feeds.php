<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    protected $table = 'Feeds';
    protected $fillable = ['wedding_id','user_id','attachment','attachment_type'];

    public function user(){
    	// return $this->hasOne(\App\Models\User::class,'id','user_id');
    	return $this->belongsTo(\App\Models\User::class,'user_id','id');
    }

	public function wedding(){
		return $this->hasOne(\App\Models\Wedding::class,'id','wedding_id');
	}
}
