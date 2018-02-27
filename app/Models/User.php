<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
	  'name', 'email', 'password', 'mobile' ,'user_type' ,'first_name' ,'last_name'
	];

	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
	  'password', 'remember_token',
	];

	public function getUserTypeAttribute($value){
		if($value == 1){
			return "bride";
		}
		if($value == 2){
			return "groom";
		}
		if($value == 3){
			return "host";
		}
		if($value == 4){
			return "vendor";
		}
	}

	public function getEmailAttribute($value){
		if(empty($value)){
			return "";
		}else{
			return $value;
		}
	}

	public function getOtpAttribute($value){
		if(empty($value)){
			return "";
		}else{
			return $value;
		}
	}

	public function getOtpVerifiedAttribute($value){
		if(empty($value) && $value!='0'){
			return "";
		}else{
			return $value;
		}
	}

	public function getProfileImageAttribute($value){
		if(empty($value)){
			return "";
		}else{
			$response = [
				'big' => url('/public/images').'/big'.$value,
				'small' => url('/public/images').'/small'.$value,
				'thumbnail' => url('/public/images').'/thumbnail'.$value,
			];
			return json_decode(json_encode($response),True);
		}
	}
}

