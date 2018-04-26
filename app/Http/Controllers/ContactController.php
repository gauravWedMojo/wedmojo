<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Response;
use \App\Models\Contacts;

class ContactController extends Controller
{
	public function sync_contacts(Request $request){
		$userDetail = $request->userDetail;
		$Invite_friends_contacts = $request->Invite_friends_contacts;
		foreach ($Invite_friends_contacts as $key => $value) {
			Contacts::firstOrCreate([
				'contact_name' => $value['contact_name'],
				'contact_number' => $value['contact_number'],
				'user_id' => $userDetail->id
			]);
		}
		$contacts = Contacts::where('user_id',$userDetail->id)
			->select('id','contact_name','contact_number','user_id')
			->get();
		$response = [
			'message' => __('messages.success.success'),
			'response' => $contacts
		];
		return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
	}
}
