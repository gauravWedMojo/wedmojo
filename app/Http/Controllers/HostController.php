<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Response;
use \App\Models\User;
use Illuminate\Validation\Rule;

class HostController extends Controller
{
    public function create_host(Request $request){
        $userDetail = $request->userDetail;
        $name = $request->name;
        $relation = $request->relation;
        $country_code = explode('-', $request->mobile)[0];
        $mobile = explode('-', $request->mobile)[1];
        $validations = [
            'name' => 'required',
            'relation' => 'required',
            'mobile' => 'required|unique:users',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $UserDetail = User::firstOrCreate(['country_code' => $country_code,'mobile' => $mobile ,'user_type' => 3]);
            $UserDetail->first_name = $name;
            $UserDetail->relation = $relation;
            $UserDetail->created_by_user_id = $userDetail->id;
            $UserDetail->save();
            $response = [
                'message' => 'success',
                'response' => $UserDetail
            ];
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }

    public function edit_host(Request $request){
        $userDetail = $request->userDetail;
        // dd($userDetail);
        $name = $request->name;
        $relation = $request->relation;
        $country_code = explode('-', $request->mobile)[0];
        $mobile = explode('-', $request->mobile)[1];
        $host_id = $request->host_id;

        $validations = [
            'name' => 'required',
            'relation' => 'required',
            'host_id' => 'required',
            'mobile' => [
                Rule::unique('users')->ignore($host_id),
            ],
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{

            $UserDetail = User::firstOrNew(['id' => $host_id ,'user_type' => 3]);
            if($UserDetail){
                $UserDetail->first_name = $name;
                $UserDetail->relation = $relation;
                $UserDetail->country_code = $country_code;
                $UserDetail->mobile = $mobile;
                $UserDetail->created_by_user_id = $userDetail->id;
                $UserDetail->save();
                $response = [
                    'message' => 'success',
                    'response' => $UserDetail
                ];
                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
            }else{
                $response = [
                    'message' => __('messages.invalid.request')
                ];
                return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
            }
        }
    }

    public function delete_host(Request $request){
        $userDetail = $request->userDetail;
        // dd($userDetail);
        $host_id = $request->host_id;
        $validations = [
            'host_id' => 'required',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $UserDetail = User::firstOrNew(['id' => $host_id ,'user_type' => 3]);
            // dd($UserDetail);
            if($UserDetail){
                $UserDetail->delete();
                $response = [
                    'message' => 'success',
                ];
                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
            }else{
                $response = [
                    'message' => __('messages.invalid.request')
                ];
                return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
            }
        }
    }

    public function get_host(Request $request){
        $userDetail = $request->userDetail;
        $hots_list = User::where(['created_by_user_id' => $userDetail->id ,'user_type' => 3])->get();
        $response = [
            'message' => 'Host list',
            'response' => $hots_list
        ];
        return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
    }

}
