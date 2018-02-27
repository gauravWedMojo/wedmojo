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

Route::post('create_category','CategoryController@Create');
Route::post('create_country','CountryController@create');
Route::get('category_list','CategoryController@List');
Route::delete('category','CategoryController@delete');





Route::group(['middleware'=>['ApiAuthentication']],function(){
	Route::post('logout','CommonController@logout');
	Route::post('setup_wedding','CommonController@setup_wedding');

	Route::post('complete_profile','CommonController@complete_profile');
	Route::post('change_password','CommonController@change_password');
	Route::post('change_passcode','CommonController@change_passcode');
	Route::post('update_profile','CommonController@update_profile');
	Route::post('home_data','UserPanelController@home_data');
	Route::post('get_data_by_category','UserPanelController@get_data_by_category');
	Route::post('sort_data','UserPanelController@sort_data');
	Route::match(['post','get'],'make_And_Get_store_favourite','UserPanelController@make_And_Get_store_favourite');
	Route::match(['post','get'],'get_top_merchant','UserPanelController@get_top_merchant');
	Route::match(['post','get'],'store_detail','UserPanelController@get_store_detail');
	Route::match(['post','get'],'scan_qr_code','UserPanelController@scan_qr_code');
	Route::match(['post','get'],'recent_stores','UserPanelController@recent_stores');
});