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
use \App\Models\HiddenFeed;
use \App\Models\FeedReport;

class FeedController extends Controller
{
	/*public function __construct(){
		$timezone = \Config::get('app.timezone');
		date_default_timezone_set('Asia/calcutta');
	}*/

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
				'caption' => $caption,
				'created_at' => time(),
				'updated_at' => time()
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
				$Feeds->feed_created_by_user_detail;  //model methos calling from Feed Model
				$Feeds->attachment;
				foreach ($Feeds->attachment as $key => $value) {
					$value->feed_created_by_user_detail;  //model methos calling from Feed Model
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
				$Feeds->feed_created_by_user_detail;  //model methos calling from Feed Model
				$Feeds->attachment;
				foreach ($Feeds->attachment as $key => $value) {
					$value->attachment = url('public/Images/FunctionFeeds/Images').'/'.$value->attachment;
				}

				$response = [
				  'messages' => 'Image_uploaded',
				  'response' => $Feeds->toArray(),
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
				$HiddenFeed = HiddenFeed::where(['user_id' => $userDetail->id])->pluck('feed_id');
				$Feeds = Feeds::where(['wedding_id' => $wedding_id])
					->whereNotIn('id',$HiddenFeed)
					->orderBy('id','desc')
					->get();
				// return $Feeds;
				foreach ($Feeds as $key => $value) {
					$value->feed_created_by_user_detail;  //model methos calling from Feed Model
					// $value->wedding; //model methos calling from Feed Model
					$value->attachment; //model methos calling from Feed Model
				}
				foreach ($Feeds as $key => $value) {
					for ($i=0; $i < count($value->attachment); $i++) { 
						if( $value->attachment[$i]->attachment_type == 1 )
							$value->attachment[$i]->attachment = url('public/Images/FunctionFeeds/Video').'/'.$value->attachment[$i]->attachment;
						if( $value->attachment[$i]->attachment_type == 2 )
							$value->attachment[$i]->attachment = url('public/Images/FunctionFeeds/Images').'/'.$value->attachment[$i]->attachment;
					}
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
	  		// dd($Feeds);
	  		$Feeds->caption = $caption;
	  		$Feeds->updated_at = time();
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
						'created_at' => time(),
						'updated_at' => time(),
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
							'created_at' => time(),
							'updated_at' => time(),
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

	public function delete_feed(Request $request){
		$userDetail = $request->userDetail;
		$feed_id = $request->feed_id;
		$key = $request->key;
		$Feeds = Feeds::find($feed_id);
		$validations = [
		   'feed_id' => 'required',
		   'key' => 'required',
		];
		$messages = [
		   'feed_id.required' => 'feed_id field is required',
		   'key.required' => 'key field is required',
		];
	 	$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}else{
	  		if(count($Feeds)){
		  		if($userDetail->id == $Feeds->user_id ){
		  			switch ($key) {
		  				/*case '1':
		  					$Feeds->status = 0;
		  					$Feeds->hide_by_user_id = $userDetail->id;
							$Feeds->save();
							$response = [
								'message' => __('messages.success.feeds_hide')
							];
							return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
		  					break;*/
		  				case '2' :
		  					Attachment::where(['feed_id' => $Feeds->id])->delete();
		  					Feeds::find($Feeds->id)->delete();
		  					$response = [
								'message' => __('messages.success.success')
							];
							return response()->json($response,trans('messages.statusCode.ACTION_COMPLETE'));
		  					break;
		  			}
				}else{
					$response = [
						'message' => __('messages.invalid.request')
					];
					return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
				}
			}else{
				$response = [
					'message' => __('messages.invalid.NOT_FOUND')
				];
				return response()->json($response,trans('messages.statusCode.NOT_FOUND'));
			}
	  	}
	}

	public function report_on_feed(Request $request){
		$userDetail = $request->userDetail;
		$feed_id = $request->feed_id;
		$Feeds = Feeds::find($feed_id);
		$message = $request->message;
		$validations = [
		   'feed_id' => 'required',
		   'message' => 'required',
		];
		$messages = [
		   'feed_id.required' => 'feed_id field is required',
		   'message.required' => 'message field is required',
		];
	 	$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}else{
	  		if(count($Feeds)){
		  		$FeedReport = FeedReport::firstOrNew(['user_id' => $userDetail->id , 'feed_id' => $feed_id]);
		  		$FeedReport->message = $message;
		  		$FeedReport->save();
		  		$FeedReportCount = FeedReport::where(['feed_id' => $feed_id])->count();
		  		if($FeedReportCount == 10 ){
		  			$Feeds->status = 0;
		  			$Feeds->save();
		  		}
		  		$response = [
		  			'message' => __('messages.success.success')
		  		];
		  		return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
		  	}else{
				$response = [
					'message' => __('messages.invalid.NOT_FOUND')
				];
				return response()->json($response,trans('messages.statusCode.NOT_FOUND'));
			}
	  	}	
	}

	public function hide_feeds(Request $request){
		$userDetail = $request->userDetail;
		$feed_id = $request->feed_id;
		$validations = [
		   'feed_id' => 'required',
		];
		$messages = [
		   'feed_id.required' => 'feed_id field is required',
		];
	 	$validator = Validator::make($request->all(),$validations,$messages);
	  	if($validator->fails()){
	      $response = [
	          'message' => $validator->errors($validator)->first()
	      ];
	      return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
	  	}
		$HiddenFeed = HiddenFeed::firstOrCreate(['feed_id' => $feed_id , 'user_id' => $userDetail->id]);
		$response = [
			'messages' => __('messages.success.success'),
			'response' => $HiddenFeed
		];
		return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
	}
}
