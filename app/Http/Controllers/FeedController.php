<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Response;
use Hash;
use Exception;
use \App\Models\Feeds;

class FeedController extends Controller
{

   public function feeds(Request $request){
		$userDetail = $request->userDetail;
		$key = $request->key;
		$video = $request->file('video');
		$image = $request->image;
		$wedding_id = $request->wedding_id;
		$caption = $request->caption;
		$validations = [
		   'key' => 'required',
		   'video' => 'required_if:key,==,1',
		   'image' => 'required_if:key,==,2',
		   'wedding_id' => 'required',
		];
		$messages = [
		   'video.required_if' => 'video field is required',
		   'image.required_if' => 'image field is required',
		   'wedding_id.required' => 'wedding_id field is required',
		];
	 	$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}else{
	  		// dd($image);
			if($key == 1){
				$path = public_path().'/'.'Images/FunctionFeeds/Video';
				$video_name = str_replace(" ","_",time().'_'.$video->getClientOriginalName());
				$video->move($path,$video_name);
				$Feeds = Feeds::firstOrCreate(['wedding_id' => $wedding_id ,'user_id' => $userDetail->id ,'attachment' => $video_name]);
				$Feeds->attachment = $video_name;
				$Feeds->attachment_type = $key;
				$Feeds->caption = $caption;
				$Feeds->save();
				$response = [
				  'messages' => 'Video_uploaded',
				  'response' => $Feeds,
				  'key' => '1',
				  'url' => url('public/Images/FunctionFeeds/Video').'/'.$video_name
				];
				return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
			}
	      	if($key == 2){
				$path = public_path().'/'.'Images/FunctionFeeds/Images';
				$image_name = str_replace(" ","_",time().'_'.$image->getClientOriginalName());
				$image->move($path,$image_name);
				$Feeds = Feeds::firstOrCreate(['wedding_id' => $wedding_id ,'user_id' => $userDetail->id ,'attachment' => $image_name]);
				$Feeds->attachment = $image_name;
				$Feeds->attachment_type = $key;
				$Feeds->caption = $caption;
				$Feeds->save();

				$response = [
				  'messages' => 'Image_uploaded',
				  'response' => $Feeds,
				  'key' => '2',
				  'url' => url('public/Images/FunctionFeeds/Images').'/'.$image_name,
				];
				return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
	      	}
	  	}
	}
	
	public function get_feeds_by_wedding(Request $request){
		$wedding_id = $request->wedding_id;
		$userDetail = $request->userDetail;
		$validations = [
		'wedding_id' => 'required',
		];
		$messages = [
		   'wedding_id.required' => 'wedding_id field is required',
		];
		$validator = Validator::make($request->all(),$validations,$messages);
		if($validator->fails()){
		   $response = [
		       'message' => $validator->errors($validator)->first()
		   ];
		   return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
		}else{
				$Feeds = Feeds::where(['wedding_id' => $wedding_id])->get();
				foreach ($Feeds as $key => $value) {
					$value->user;  //model methos calling from Feed Model
					$value->wedding; //model methos calling from Feed Model
				}
				$response = [
					'messages' => __('messages.success.success'),
					'response' => $Feeds
				];
				return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
		}
	} 	

	public function update_feed(Request $request){
		$wedding_id = $request->wedding_id;
		$validations = [
		   'key' => 'required',
		   'video' => 'required_if:key,==,1',
		   'image' => 'required_if:key,==,2',
		   'wedding_id' => 'required',
		];
		$messages = [
		   'video.required_if' => 'video field is required',
		   'image.required_if' => 'image field is required',
		   'wedding_id.required' => 'wedding_id field is required',
		];

		$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}
	}
}
