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

Route::get('/job',function(){
	return App\Job::all();
});

Route::get('dashboard/getDashboard','RestController@getDashboard');
Route::get('dashboard/getJobCategory','RestController@getJobCategory');
Route::get('dashboard/getJobList','RestController@getJobList');
Route::get('dashboard/getJobListSumary','RestController@getJobListSumary');
Route::get('dashboard/getJobListRecomended','RestController@getJobListRecomended');

Route::get('job/getJobOpen','RestController@getJobOpen');

Route::get('job/getJobProgress','RestController@getJobProgress');

Route::get('payment/getJobPayment','RestController@getJobPayment');
Route::get('payment/getJobPaymentDetail','RestController@getJobPaymentDetail');
