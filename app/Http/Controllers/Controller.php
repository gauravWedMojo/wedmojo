<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function Validation($request,$validations , $messages = null ){
 		$validator = Validator::make($request,$validations,$messages);
 		if($validator->fails()){
		 	$response = [
				'message' => $validator->errors($validator)->first()
			];
		   return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
 		}else{
 			return 1;
 		}
 	}
}
