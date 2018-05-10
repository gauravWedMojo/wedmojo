<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('test_procedure',function(){
	$Param1 = 1;
	$param2 = 'gauravmrvh1@gmail.com';
	return DB::select('call getUsers');
	return DB::select('call get_user_by_id_or_email(?,?)',array($Param1,$param2));
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



/////////////////////////////////ADMIN ROUTES /////////////////////////////////////////

Route::get('/admin', function (Request $request) {
	if(!Auth::guard('admin')->check())
   	return view('welcome');
   else
   	return redirect('/admin/dashboard');
});
Route::group(['prefix' => '/admin' , 'middleware' => ['admin']],function(){
		Route::match(['post','get'],'/profile','AdminController@profile');
		Route::match(['post','get'],'/edit-profile','AdminController@edit');
		Route::match(['post','get'],'/change-password','AdminController@change_password');
		Route::get('/dashboard',['uses' => 'AdminController@dashboard' , 'as' => 'admin.dashboard']);
		Route::match(['get','post'],'/user-management',['uses' => 'AdminController@user_management' , 'as' => 'admin.user_management']);
		Route::match(['get','post'],'/block-unblock-user/{user_id}/{status}',['uses' => 'AdminController@block_unblock_user' , 'as' => 'admin.block_unblock_user'])->where(['user_id','[0-9]+','status' => '[0-9]+']);
});
Route::post('admin/login_post','AdminController@index');
Route::get('/admin/logout',['uses' => 'AdminController@logout' , 'as' => 'admin.logout']);
Route::match(['get','post'],'/admin/forget-password',['uses' => 'AdminController@forget_password' , 'as' => 'admin.forget-password']);
Route::match(['get','post'],'/admin/reset-password/{token?}',['uses' => 'AdminController@reset_password' , 'as' => 'admin.reset-password']);
Route::post('check','AdminController@check');