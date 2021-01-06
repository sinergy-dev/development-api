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

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
// use Mail;
use App\Mail\JoinPartnerModerator;

Route::get('testing','TestController@postGuzzleRequest');
Route::post('testingMinio','TestController@testingMinio');
Route::get('/testWebexAccessToken','RestController@getWebexAccessToken');

Route::get('testingToken','TestController@tokenTest');

Route::get('testPDFReport','RestController@testPDFReport');
Route::get('testGetImage',function(){
	return response()->file(Storage::disk('ftp')->get('aaa.png'));
});

Route::get('testString',function(){
	return "This is string from testString method";
});

Route::get('testEmail/{id}',function($id){
	$activity = App\Candidate_engineer_history::select('history_detail')->where('id_candidate',$id)
				->orderBy('history_date','DESC')->get();

	$partner = App\Candidate_engineer::where('id',$id)->first();
	return new App\Mail\JoinPartnerModerator("a",$partner,$activity,'[EOD] Congrats! Interview Session Scheduled');
});

Route::get('testEmailDinar',function(){
	$activity = App\Candidate_engineer_history::select('history_detail')->where('id_candidate',88)
				->orderBy('history_date','DESC')->get();

	$partner = App\Candidate_engineer::where('id',88)->first();
	// Mail::to('agastya@gmail.com')->send(new JoinPartnerModerator("a",$partner,$activity,'[EOD] Congrats! Interview Session Scheduled'));

	return new App\Mail\JoinPartnerModerator("a",$partner,$activity,'[EOD] Congrats! Interview Session Scheduled');
});

Route::post('partner/getNewPartnerIdentifier','RestController@getNewPartnerIdentifier');

Route::get('arrayTableDynamic',function(){
	return App\Users::select('id','name','email')->get()->all();
	return collect([
        ["name" => "Rama","email"=> "agastya@sinergy.co.id"],
        ["name" => "Bril","email"=> "brillyan@sinergy.co.id"],
        ["name" => "Dinar","email"=> "ladinar@sinergy.co.id"],
        ["name" => "Faiqoh","email"=> "faiqoh@sinergy.co.id"],
        ["name" => "Tito","email"=> "tito@sinergy.co.id"]
    ]);
});

// Route::get('arrayTableDynamic',function(){
// 	return collect([
// 		'name' => 'Rama Agastya',
// 		'email' => 'agastya@sinergy.co.id',
// 		'username' => 'rama11',
// 		'address' => 'Jl Jend Gatot Subroto Kav 56 Ged Adhi Graha Lt 15 Suite 1501'
// 	]);
// });

Route::get('arrayTableDynamicSpecific',function(Request $req){
	$user = App\Users::find($req->id);
	return collect([
		'name' => $user->name,
		'email' => $user->email,
		'username' => $user->name . $user->id,
		'address' => $user->address
	]);
	return $user->name;
	return collect([
		'name' => 'Rama Agastya',
		'email' => 'agastya@sinergy.co.id',
		'username' => 'rama11',
		'address' => 'Jl Jend Gatot Subroto Kav 56 Ged Adhi Graha Lt 15 Suite 1501'
	]);
});

Route::get('testStorage','RestController@testStorage');
Route::get('sendNotification','API\APIRestController@sendNotification');
Route::get('sendNotificationToAndroid','RestController@getTokenToNotification2');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/job',function(){
	return App\Job::all();
});

Route::get('dashboard/getDashboard','RestController@getDashboard');
Route::get('dashboard/getDashboardModerator','RestController@getDashboardModerator');
Route::get('dashboard/getTopEngineer','RestController@getTopEngineer');
// Route::post('dashboard/getDashboard','RestController@getDashboard');
Route::get('dashboard/getJobCategory','RestController@getJobCategory');
Route::get('dashboard/getJobCategoryPaging','RestController@getJobCategoryPaging');
Route::get('dashboard/getJobCategoryAll','RestController@getJobCategoryAll');
Route::get('dashboard/getJobCategoryMain','RestController@getJobCategoryMain');
Route::get('dashboard/getJobCategory/search','RestController@getJobCategorySearch');

Route::get('dashboard/getJobList','RestController@getJobList');
Route::get('dashboard/getJobListSumary','RestController@getJobListSumary');
Route::get('dashboard/getJobListAndSumary','RestController@getJobListAndSumary');
Route::get('dashboard/getJobListAndSumaryEngineer','RestController@getJobListAndSumaryEngineer');
Route::get('dashboard/getJobListRecomended','RestController@getJobListRecomended');

Route::get('dashboard/getJobListAndSumary/paginate','RestController@getJobListAndSumaryPaginate');
Route::get('dashboard/getJobListAndSumary/search','RestController@getJobListAndSumarySearch');
Route::post('dashboard/getJobListAndSumary/FilterStatus','RestController@getJobListAndSumaryFilterStatus');

Route::get('job/getJobByCategory','RestController@getJobByCategory');
Route::get('job/getJobOpen','RestController@getJobOpen');
Route::get('job/getJopApplyer','RestController@getJopApplyer');
Route::get('job/getPICbyClient','RestController@getPICbyClient');

Route::get('job/getJobProgress','RestController@getJobProgress');
Route::get('job/getJobForLoAPDF','RestController@getJobForLoAPDF');
Route::get('job/getJobReportPDF','API\APIRestController@getJobReportPDF');
Route::get('job/getJobBastPDF','API\APIRestController@getJobBastPDF');

Route::post('job/getJobBast','RestController@createBast');
Route::get('job/createBastTesting','RestController@createBastTesting');


Route::get('payment/getJobPayment','RestController@getJobPayment');
Route::get('payment/getJobPaymentDetail','RestController@getJobPaymentDetail');

Route::post('job/postJobApply','RestController@postJobApply');
Route::post('job/postJobStart','RestController@postJobStart');
Route::post('job/postJobUpdate','RestController@postJobUpdate');
Route::post('job/postJobFinish','RestController@postJobFinish');
Route::post('job/postApplyerUpdate','RestController@postApplyerUpdate');

Route::post('job/postChatModerator','RestController@postChatModerator');
Route::post('job/postReviewedByModerator','RestController@postReviewedByModerator');
Route::post('job/postFinishedByModerator','RestController@postFinishedByModerator');
Route::post('job/postPayedByModeratorFirst','RestController@postPayedByModeratorFirst');
Route::post('job/postPayedByModeratorSecond','RestController@postPayedByModeratorSecond');
Route::post('job/postPayedByModeratorInvoice','RestController@postPayedByModeratorInvoice');
Route::post('job/postUpdatePayment','RestController@postUpdatePayment');


Route::post('setting/category/postCategory','RestController@postCategory');
Route::post('setting/category/postCategoryMain','RestController@postCategoryMain');
Route::post('setting/category/postUpdateCategory','RestController@postUpdateCategory');

// Web API
Route::get('job/createJob/getParameterClientAll','RestController@getParameterClientAll');
Route::get('job/createJob/getParameterLocationAll','RestController@getParameterLocationAll');
Route::get('job/createJob/getParameterPicAll','RestController@getParameterPicAll');
Route::get('job/createJob/getParameterLevelAll','RestController@getParameterLevelAll');
Route::get('job/createJob/getParameterCategoryAll','RestController@getParameterCategoryAll');
Route::get('job/createJob/getParameterFinalize','RestController@getParameterFinalize');
Route::post('job/createJob/postPublishJobs','RestController@postPublishJobs');
Route::post('job/updateJob/postPublishJobsEdit','RestController@postPublishJobsEdit');
Route::post('job/createJob/postQRRecive','RestController@postQRRecive');
Route::post('job/createJob/postPDFRecive','RestController@postPDFRecive');
Route::post('job/createJob/postLetter','RestController@postLetter');

Route::get('engineer/getEngineerList','RestController@getEngineerList');
Route::get('engineer/getEngineerList/search','RestController@getEngineerListSearch');
Route::post('engineer/postNewEngineer','RestController@postNewEngineer');
Route::post('engineer/updateEngineerData','RestController@updateEngineerData');

Route::get('client/getClientList','RestController@getClientList');
Route::get('client/getClientList/search','RestController@getClientListSearch');
Route::post('client/postNewClient','RestController@postNewClient');
Route::post('client/updateClient','RestController@updateClient');

Route::get('job/getStatusRequestItem','RestController@getRequestitem');
Route::post('job/postStatusRequestItem','RestController@postStatusRequestItem');
Route::post('job/postStatusRequestSupport','RestController@postStatusRequestSupport');

Route::get('job/getChatModeratorEach','RestController@getChatModeratorEach');
Route::get('job/getJobSupportEach','API\APIRestController@getJobSupportEach');

Route::post('join/postBasicJoin','RestController@postBasicJoin');
Route::post('join/postAdvancedJoin','RestController@postAdvancedJoin');
Route::post('join/postSubmitPartner','RestController@postSubmitPartner');
Route::post('join/postScheduleInterview','RestController@postScheduleInterview');
Route::post('join/postStartInterview','RestController@postStartInterview');
Route::post('join/postResultInterview','RestController@postResultInterview');
Route::post('join/postAgreementInterview','RestController@postAgreementInterview');
Route::post('join/postPartnerAgreement','RestController@postPartnerAgreement');
Route::get('partner/getNewPartnerList','RestController@getNewPartnerList');
Route::get('partner/getDetailPartnerList','RestController@getDetailPartnerList');
Route::get('partner/getPartnerList/search','RestController@getPartnerListSearch');
Route::get('partner/getNewPartnerSelectedList','RestController@getNewPartnerSelectedList');


Route::auth();


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

