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



Route::group(['middleware'=>['ApiAuthentication']],function(){
	Route::post('logout','CommonController@logout');
	Route::post('update_profile','CommonController@update_profile');
	Route::post('setup_wedding','CommonController@setup_wedding');
	Route::post('create_host','CommonController@create_host');
	Route::post('create_function','CommonController@create_function');
	Route::post('change_password','CommonController@change_password');
	Route::post('edit_host','CommonController@edit_host');
	Route::post('delete_host','CommonController@delete_host');
	Route::match(['get','post'],'get_host','CommonController@get_host');
});