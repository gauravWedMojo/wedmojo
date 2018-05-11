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
		$skip = 0;
		$take = 15;
		if($request->page > 0 ){
			$skip = $request->page*$take;
		}
		// dd($skip);
		$Invite_friends_contacts = $request->Invite_friends_contacts;
		switch($request->method()) {
			case 'POST' :
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
				break;

			case 'GET':
				$contacts = Contacts::where('user_id',$userDetail->id)
					->select('id','contact_name','contact_number','user_id')
					->orderBy('contact_name','asc')
					->skip($skip)
					->take($take)
					->get();
				$response = [
					'message' => __('messages.success.success'),
					'response' => $contacts
				];
				return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
				break;
		}
	}
}
