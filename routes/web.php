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

Route::get('/', function () {
    return view('welcome');
});




Route::get('test_procedure',function(){
	$Param1 = 1;
	$param2 = 'gauravmrvh1@gmail.com';
	return DB::select('call getUsers');
	return DB::select('call get_user_by_id_or_email(?,?)',array($Param1,$param2));
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
