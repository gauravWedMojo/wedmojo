<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\Wed_Function;
use \App\Models\User;
use Log;
use Response;

class FunctionController extends Controller
{
   
   	public function create_function(Request $request){
        $UserDetail = $request->userDetail;
        $function_name = $request->function_name;
        $function_image = $request->function_image;
        $function_date = $request->function_date;
        $validations = [
            'function_name' => 'required',
            'function_image' => 'required',
            'function_date' => 'required',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $function_detail = Wed_Function::firstOrCreate(['function_name' => $function_name, 'function_date' => $function_date,'created_by_user_id' => $UserDetail->id]);
            $destinationPath = public_path().'/'.'Images/FunctionImages';
           /* if( file_exists( $destinationPath.'/'.$function_detail->function_image ) ) {
                unlink($destinationPath.'/'.$function_detail->function_image);
            }*/
            $fileName = time().'_'.$function_image->getClientOriginalName();
            $function_image->move($destinationPath,$fileName);
            $function_detail->function_image = $fileName;
            $function_detail->save();
            $function_detail->function_image = url('public/Images/FunctionImages').'/'.$function_detail->function_image;
            $response = [
                'message' => 'success',
                'response' => $function_detail
            ];
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }

    public function get_function(Request $request){
    	$UserDetail = $request->userDetail;	
    	$function_detail = Wed_Function::where(['created_by_user_id' => $UserDetail->id])->get();
    	foreach ($function_detail as $key => $value) {
    		$value->user;
	   	}
    	$response = [
            'message' => 'success',
            'response' => $function_detail
        ];
        return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
    }

    public function edit_function(Request $request){
        $UserDetail = $request->userDetail;    
        $function_name = $request->function_name;
        $function_date = $request->function_date;
        $function_image = $request->function_image;
        $function_id = $request->function_id;
        $validations = [
            'function_name' => 'required',
            'function_image' => 'required',
            'function_date' => 'required',
            'function_id' => 'required',
        ];
        $messages = [
            'function_name.required' => 'function_name field required',
            'function_image.required' => 'function_image field required',
            'function_date.required' => 'function_date field required',
            'function_id.required' => 'function_id field required',
        ];
        $validator = Validator::make($request->all(),$validations,$messages);
        if( $validator->fails() ){
            $response = [
                'message'=>$validator->errors($validator)->first()
            ];
            return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $function_detail = Wed_Function::find($function_id);

            $destinationPath = public_path().'/'.'Images/FunctionImages';
            $fileName = time().'_'.$function_image->getClientOriginalName();
            $function_image->move($destinationPath,$fileName);
            $function_detail->function_name = $function_name;
            $function_detail->function_date = $function_date;
            $function_detail->function_image = $fileName;
            $function_detail->save();
            $function_detail->function_image = url('public/Images/FunctionImages').'/'.$function_detail->function_image;
            $response = [
                'message' => __('messages.success.success'),
                'response' => $function_detail
            ];
            return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }

    }
}
