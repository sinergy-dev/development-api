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

Route::get('sendNotification','API\APIRestController@sendNotification');
Route::get('sendNotificationToAndroid','RestController@getTokenToNotification');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/job',function(){
	return App\Job::all();
});

Route::get('dashboard/getDashboard','RestController@getDashboard');
// Route::post('dashboard/getDashboard','RestController@getDashboard');
Route::get('dashboard/getJobCategory','RestController@getJobCategory');
Route::get('dashboard/getJobCategoryAll','RestController@getJobCategoryAll');

Route::get('dashboard/getJobList','RestController@getJobList');
Route::get('dashboard/getJobListSumary','RestController@getJobListSumary');
Route::get('dashboard/getJobListAndSumary','RestController@getJobListAndSumary');
Route::get('dashboard/getJobListRecomended','RestController@getJobListRecomended');

Route::get('job/getJobByCategory','RestController@getJobByCategory');
Route::get('job/getJobOpen','RestController@getJobOpen');
Route::get('job/getJopApplyer','RestController@getJopApplyer');

Route::get('job/getJobProgress','RestController@getJobProgress');
Route::get('job/getJobForPDF','RestController@getJobForPDF');

Route::get('payment/getJobPayment','RestController@getJobPayment');
Route::get('payment/getJobPaymentDetail','RestController@getJobPaymentDetail');

Route::post('job/postJobApply','RestController@postJobApply');
Route::post('job/postJobStart','RestController@postJobStart');
Route::post('job/postJobUpdate','RestController@postJobUpdate');
Route::post('job/postJobFinish','RestController@postJobFinish');
Route::post('job/postApplyerUpdate','RestController@postApplyerUpdate');

Route::post('job/postReviewedByModerator','RestController@postReviewedByModerator');
Route::post('job/postFinishedByModerator','RestController@postFinishedByModerator');
Route::post('job/postPayedByModeratorFirst','RestController@postPayedByModeratorFirst');
Route::post('job/postPayedByModeratorSecond','RestController@postPayedByModeratorSecond');
Route::post('job/postPayedByModeratorInvoice','RestController@postPayedByModeratorInvoice');
Route::post('job/postUpdatePayment','RestController@postUpdatePayment');

// Web API
Route::get('job/createJob/getParameterClientAll','RestController@getParameterClientAll');
Route::get('job/createJob/getParameterLocationAll','RestController@getParameterLocationAll');
Route::get('job/createJob/getParameterPicAll','RestController@getParameterPicAll');
Route::get('job/createJob/getParameterLevelAll','RestController@getParameterLevelAll');
Route::get('job/createJob/getParameterCategoryAll','RestController@getParameterCategoryAll');
Route::get('job/createJob/getParameterFinalize','RestController@getParameterFinalize');
Route::post('job/createJob/postPublishJobs','RestController@postPublishJobs');
Route::post('job/createJob/postQRRecive','RestController@postQRRecive');
Route::post('job/createJob/postPDFRecive','RestController@postPDFRecive');
Route::post('job/createJob/postLetter','RestController@postLetter');

Route::get('engineer/getEngineerList','RestController@getEngineerList');
Route::post('engineer/postNewEngineer','RestController@postNewEngineer');
Route::post('engineer/updateEngineerData','RestController@updateEngineerData');

Route::get('client/getClientList','RestController@getClientList');
Route::post('client/postNewClient','RestController@postNewClient');



Route::auth();


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
