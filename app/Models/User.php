<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	
	protected $fillable = [
	  'name', 'email', 'password', 'mobile' ,'user_type' ,'first_name' ,'last_name','country_code'
	];

	protected $hidden = [
	  'password',
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
			return "0";
		}else{
			return $value;
		}
	}

	public function getProfileImageAttribute($value){
		if(empty($value)){
			$response = [
				'big' => "",
				'small' => "",
				'thumbnail' => "",
			];
			return json_decode(json_encode($response),True);
		}else{
			$response = [
				'big' => url('/public/Images').'/big'.$value,
				'small' => url('/public/Images').'/small'.$value,
				'thumbnail' => url('/public/Images').'/thumbnail'.$value,
			];
			return json_decode(json_encode($response),True);
		}
	}
}

