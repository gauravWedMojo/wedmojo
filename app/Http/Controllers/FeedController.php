<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Response;
use Hash;
use Exception;
use \App\Models\Feeds;
use \App\Models\Attachment;
use \App\Models\Wedding;

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
		   'video' => 'required_if:key,==,1||array',
		   'image' => 'required_if:key,==,2|array',
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
	  		
	  		$Feeds = Feeds::insertGetId([
				'wedding_id' => $wedding_id ,
				'user_id' => $userDetail->id ,
				'caption' => $caption
			]);
			if($key == 1){
				foreach ($video as $index => $value) {
					$path = public_path().'/'.'Images/FunctionFeeds/Video';
					$video_name = str_replace(" ","_",time().$index.'_'.$video[$index]->getClientOriginalName());
					$video[$index]->move($path,$video_name);
					$Attachment = Attachment::firstOrCreate([
						'feed_id' => $Feeds, 
						'attachment' => $video_name , 
						'attachment_type' => $key ,
					]);
				}
				$Feeds = Feeds::find($Feeds);
				$Feeds->attachment;
				foreach ($Feeds->attachment as $key => $value) {
					$value->attachment = url('public/Images/FunctionFeeds/Video').'/'.$value->attachment;
				}
				$response = [
				  'messages' => 'Video_uploaded',
				  'response' => $Feeds,
				];
				return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
			}
      	if($key == 2){
	      	foreach ($image as $index => $value) {
					$path = public_path().'/'.'Images/FunctionFeeds/Images';
					$image_name = str_replace(" ","_",time().$index.'_'.$image[$index]->getClientOriginalName());
					$image[$index]->move($path,$image_name);
					$Attachment = Attachment::firstOrCreate([
						'feed_id' => $Feeds, 
						'attachment' => $image_name , 
						'attachment_type' => $key ,
					]);
				}
				$Feeds = Feeds::find($Feeds);
				$Feeds->attachment;
				foreach ($Feeds->attachment as $key => $value) {
					$value->attachment = url('public/Images/FunctionFeeds/Images').'/'.$value->attachment;
				}
				$response = [
				  'messages' => 'Image_uploaded',
				  'response' => $Feeds,
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
					$value->attachment; //model methos calling from Feed Model
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
		$feed_id = $request->feed_id;
		$caption = $request->caption;
		$key = $request->key;
		$video = $request->file('video');
		$image = $request->image;
		// dd($image);
		$validations = [
		   'key' => 'required',
		   'video' => 'required_if:key,==,1|array',
		   'image' => 'required_if:key,==,2|array',
		   'wedding_id' => 'required',
		   'feed_id' => 'required'
		];
		$messages = [
		   'video.required_if' => 'video field is required|array',
		   'image.required_if' => 'image field is required|array',
		   'wedding_id.required' => 'wedding_id field is required',
		   'feed_id.required' => 'feed_id field is required'
		];

		$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}else{
	  		$Feeds = Feeds::where(['wedding_id' => $wedding_id , 'id' => $feed_id])->first();
	  		$Feeds->caption = $caption;
	  		$Feeds->save();
	  		// get , delete from db and unlink files
		  		$Attachment = Attachment::where(['feed_id' => $feed_id])->get();
		  		// dd($key);
		  		if(count($Attachment)) {
			  		foreach ($Attachment as $index => $value) {
			  			if($value->attachment_type == 1){
			  				if(file_exists(public_path('/Images/FunctionFeeds/Video').'/'.$value->attachment)) {
								unlink(public_path('/Images/FunctionFeeds/Video').'/'.$value->attachment);	  					
			  				}
			  			}
			  			if($value->attachment_type == 2){
			  				if(file_exists(public_path('/Images/FunctionFeeds/Images').'/'.$value->attachment)){
								unlink(public_path('/Images/FunctionFeeds/Images').'/'.$value->attachment);	  					
			  				}
			  			}
			  		}
			  	}
			  	Attachment::where(['feed_id' => $feed_id])->delete();
		  	// END
		  	if($key == 1){ // VIDEO
				foreach ($video as $index => $value) {
					$path = public_path().'/'.'Images/FunctionFeeds/Video';
					$video_name = str_replace(" ","_",time().$index.'_'.$video[$index]->getClientOriginalName());
					$video[$index]->move($path,$video_name);
					$Attachment = Attachment::firstOrCreate([
						'feed_id' => $feed_id, 
						'attachment' => $video_name , 
						'attachment_type' => $key ,
					]);
				}
				$Feeds = Feeds::find($feed_id);
				$Feeds->attachment;
				foreach ($Feeds->attachment as $index => $value) {
					$value->attachment = url('public/Images/FunctionFeeds/Video').'/'.$value->attachment;
				}
				$response = [
				  'messages' => 'Video_uploaded',
				  'response' => $Feeds,
				];
				return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
			}
      	if($key == 2){ //IMAGES
	      	foreach ($image as $index => $value) {
					$path = public_path().'/'.'Images/FunctionFeeds/Images';
					$image_name = str_replace(" ","_",time().$index.'_'.$image[$index]->getClientOriginalName());
					$image[$index]->move($path,$image_name);
					$Attachment = Attachment::firstOrCreate([
						'feed_id' => $feed_id, 
						'attachment' => $image_name , 
						'attachment_type' => $key ,
					]);
				}
				$Get_Feeds = Feeds::find($feed_id);
				$Get_Feeds->attachment;
				foreach ($Get_Feeds->attachment as $key => $value) {
					$value->attachment = url('public/Images/FunctionFeeds/Images').'/'.$value->attachment;
				}
				$response = [
				  'messages' => 'Image_uploaded',
				  'response' => $Get_Feeds,
				];
				return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
      	}
	  	}
	}
}
