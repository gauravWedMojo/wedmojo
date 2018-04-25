<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wed_Function extends Model
{
	protected $table = 'function';
	protected $fillable = ['function_name','function_image','function_date','created_by_user_id',];

	public function user(){
		return $this->hasOne(\App\Models\User::class,'id','created_by_user_id');
	}
}
