<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

class SendMail extends Controller
{
	public static function sendMail($data){
		try{
			$ok = Mail::send(['html'=>'forgetLink'], $data, function($message) use ($data){
				$message->to($data['email'])
				->subject ('Reset Password Link');
				$message->from('techfluper@gmail.com');
        	});
        	switch ($data['type']) {
        		case '1':
        			return 1;
        			break;
        		
        		case '2' :
        			$response = [
		        		'message' => __('messages.success.success'),
		        		'response' => 1
		        	];
		        	return response()->json($response,200);
		        	break;
        	}
		}catch(Exception $e){
			$response=[
                'message' => $e->getMessage(),
                'status' => 0
            ];
            return response()->json($response,200);
		}
	}
}
