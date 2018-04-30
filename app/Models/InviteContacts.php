<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteContacts extends Model
{
	protected $table = 'invites';
	protected $fillable = ['contact_id','function_id'
	];


	public function contact(){
		return $this->hasOne(\App\Models\Contacts::class,'id','contact_id')->select(['id','contact_name','contact_number','user_id']);
	}

	public static function get_invite_contacts($function_id){
		return Self::with('contact')
			->where(['function_id' => $function_id])
			->select('id','contact_id','function_id')
			->get();
	}
}
