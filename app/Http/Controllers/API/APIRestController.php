<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use DB;
use Storage;
use App\Users;
use App\Job;
use App\Job_applyer;
use App\Job_history;
use App\Job_category;
use App\Job_level;
use App\Job_category_main;
use App\Job_pic;
use App\Job_review;
use App\Job_request_item;
use App\Engineer_category;
use App\Payment;
use App\Payment_history;
use App\Customer;
use App\Location;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\RestController;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

use PDF;

class APIRestController extends Controller
{
	// Untuk di activity Dashboard
	// Ada 5 get function

	// With Token
	public function getDashboard(Request $req){
		return collect(['users' => Users::find($req->user()->id)]);
	}

	public function getJobCategory(){
		return collect(['job_category' => Job_category::all()]);
	}

	public function getJobCategoryAll(){
		return collect(['job_category_all' => Job_category_main::with('category')->get()]);
	}

	public function getJobList(){
		return collect(['job' => Job::with('category')->where('job_status','Open')->get()]);
	}

	public function getJobListSumary(Request $req){
		return collect(['job' => Job::with(['customer','location'])->find($req->id_job)]);
	}

	public function getJobListAndSumary(Request $req){
		return collect(['job' => Job::with(['customer','location','category'])->get()]);
	}

	public function getJobListRecomended(Request $req){
		return collect(['job' => Job::whereIn(
			'id_category',
			Engineer_category::where('id_engineer',$req->id_engineer)
				->pluck('id_category')
				->all()
			)
		->get()]);
	}

	// Untuk di activity Job Detail dan Job Progress

	// Dalam function di bawah ini nanti untuk frontend
	// bisa di tambahkan untuk if condition `status` "Open"
	// Jika open maka akan muncul button cancel dan apply
	// Dan jika `status` = "Approved" maka akan muncul button start

	public function getJobOpen(Request $req){
		return collect([
			'job' => Job::with([
				'customer',
				'category',
				'level',
				'location',
				'pic'
			])->find($req->id_job)
		]);
	}

	public function getJopApplyer(Request $req){
		return collect([
			'applyer' => Job_applyer::with('user')->where('id_job',$req->id_job)->get()
		]);
	}

	// Untuk function di bawah ini akan mengambil job
	// berserta array progress di dalamnya

	public function getJobProgress(Request $req){
		return collect([
			'job' => Job::with(['progress','customer','location','level','pic','category'])->find($req->id_job),
			'progress' => Job_history::with('user')->where('id_job',$req->id_job)->orderBy('date_time','DESC')->get()
		]);
	}

	public function getJobPayment(Request $req){
		return collect([
			'payment' => Payment::with(['progress','lastest_progress','job'])
				->where('payment_to',$req->user()->id)
				->get()
		]);
	}

	public function getJobPaymentDetail(Request $req){
		return collect(['payment' => Payment::with(['progress','job'])->find($req->id_payment)]);
	}

	public function getJobByCategory(Request $req){


		// return collect(['job' => Job::with(['category','customer','location'])->get()]);
		return collect([
			'job' => Job::with(['category','customer','location'])
				->whereIn('id', Job_applyer::where('id_engineer',$req->user()->id)->pluck('id_job'))
				->get(),
			'id_engineer' => $req->user()->id]);
	}

	public function postJobApply(Request $req){
		$applyer = new Job_applyer();
		$applyer->id_job = $req->id_job;
		$applyer->id_engineer = $req->user()->id;
		$applyer->status = "Pending";
		$applyer->date_add = Carbon::now()->toDateTimeString();
		$applyer->save();

		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->user()->id;
		$history->id_activity = 2;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Apply job";
		$history->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Users::find($req->user()->id)->email,
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Apply job",
			Job::find($req->id_job)->job_name . " has been applied for, immediately do further checks",
			$history->id,
			$req->id_job
		);

		return $applyer;
	}

	public function postJobStart(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->user()->id;
		$history->id_activity = 4;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Job started";
		$history->save();

		$start_job = Job::find($req->id_job);
		$start_job->job_status = "Progress";
		$start_job->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Users::find($req->user()->id)->email,
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Job started",
			"Has started the '" . Job::find($req->id_job)->job_name .  "' job, immediately monitoring his activities",
			$history->id,
			$req->id_job
		);

		return $history;
	}

	public function postJobUpdate(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->user()->id;
		$history->id_activity = 5;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Update Day " . 
			(Carbon::parse(
				Job_history::where('id_user',$req->user()->id)
					->where('id_activity',4)
					->where('id_job',$req->id_job)
					->first()
					->date_time
				)->diffInDays(Carbon::now()
			) + 1) . " - " . $req->detail_activity;
		$history->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Users::find($req->user()->id)->email,
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Job Update",
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " has updated this job \n[" . Job::find($req->id_job)->job_name . "] " . $req->detail_activity,
			$history->id,
			$req->id_job
		);

		return $history;
	}

	public function postJobRequestItem(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->user()->id;
		$history->id_activity = 5;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Update Day " . 
			(Carbon::parse(
				Job_history::where('id_user',$req->user()->id)
					->where('id_activity',4)
					->where('id_job',$req->id_job)
					->first()
					->date_time
				)->diffInDays(Carbon::now()
			) + 1) . " - " . ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Request Item";
		$history->save();

		$request_item = new Job_request_item();
		$request_item->id_job = $req->id_job;
		$request_item->id_engineer = $req->user()->id;
		$request_item->name_item = $req->name_item;
		$request_item->function_item = $req->function_item;
		$documentation = $req->file('documentation_item');
		$documentation->storeAs(
			"public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/request_item",
			$documentation->getClientOriginalName()
		);
		$request_item->documentation_item = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/request_item/" . $documentation->getClientOriginalName();
		$request_item->invoice_item = "alamat harga beli";
		$request_item->status_item = "Requested";
		$request_item->price_item = $req->price_item;
		$request_item->date_add = Carbon::now()->toDateTimeString(); 
		$request_item->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Users::find($req->user()->id)->email,
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Request Item",
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " make requests for goods to continue work \n[" . Job::find($req->id_job)->job_name . "]",
			$history->id,
			$req->id_job
		);

		return $history;
	}

	public function postJobFinish(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->user()->id;
		$history->id_activity = 6;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Finish jobs ready to review";
		$history->save();



		$review = new Job_review();
		$review->id_job = $req->id_job;
		$review->id_history = $history->id;
		$review->job_summary = $req->job_summary;
		$review->job_rootcause = $req->job_rootcause;
		$review->job_countermeasure = $req->job_countermeasure;
		if(isset($req->job_documentation)){
			$documentation = $req->file('job_documentation');
			$documentation->storeAs(
				"public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/job_documentation",
				$documentation->getClientOriginalName()
			);
			// $documentation->move("storage/job_documentation/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation", $documentation->getClientOriginalName());
			// $review->job_documentation = "storage/job_documentation/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/" . $documentation->getClientOriginalName();
			$review->job_documentation = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/job_documentation/" . $documentation->getClientOriginalName();


		} else {
			$review->job_documentation = "file";
		}
		$review->job_report = "file";
		$review->date_add = Carbon::now()->toDateTimeString();
		$review->save();

		$source = new RestController();
		$source = $source->getJobForLoAPDF($req);
		$source_review = Job_review::where('id_job',$req->id_job)->first();

		$data = [
    		"job_title" => $source['job']['job_name'],
    		"job_category" => $source['job']['category']['category_name'],
    		"job_location" => $source['job']['location']['long_location'],
    		"job_address" => $source['job']['job_location'],

    		"job_description" => explode("\n",$source['job']['job_description']),
    		"job_requirment" => explode("\n",$source['job']['job_requrment']),
    	
    		"job_customer" => $source['job']['customer']['customer_name'],
    		"job_customer_address" => $source['job']['customer']['address'],
    		"job_pic" => $source['job']['pic']['pic_name'],
    		"job_pic_phone" => $source['job']['pic']['pic_phone'],
    		"job_pic_email" => $source['job']['pic']['pic_mail'],
    		"job_progress" => Job_history::where('id_job',$req->id_job)->get(),

    		"job_summary" => $source_review->job_summary,
    		"job_rootcause" => $source_review->job_rootcause,
    		"job_countermeasure" => $source_review->job_countermeasure,
    		"job_documentation" => $source_review->job_documentation,
    	];

		$pdf = PDF::loadView('pdf.report',compact('data'));
		$name_report_pdf = "job_report_" . Carbon::now()->timestamp;
    	Storage::put("public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/job_report/" . $name_report_pdf . ".pdf", $pdf->output());

    	$review->job_report = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/job_report/" . $name_report_pdf . ".pdf";
    	$review->save();
		
		$this->sendNotification(
			'moderator@sinergy.co.id',
			Users::find($req->user()->id)->email,
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " Job Finish",
			ucfirst(explode("@",Users::find($req->user()->id)->email)[0]) . " has finished this job and ready to review.\n[" . Job::find($req->id_job)->job_name . "]",
			$history->id,
			$req->id_job
		);

		return $review;
	}

	public function postApplyerUpdate(Request $req){

		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 3;
		$history->date_time = Carbon::now()->toDateTimeString();
		// $history->detail_activity = "Moderator accept " + ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) + " Apply";
		$history->detail_activity = "Moderator accept Apply";
		$history->save();

		$applyer_accept = Job_applyer::where('id_job',$req->id_job)
			->where('id_engineer',$req->id_engineer)
			->first();

		$applyer_accept->status = "Accept";
		$applyer_accept->save();

		$applyer_rejects = Job_applyer::where('id_job',$req->id_job)
			->where('id_engineer','<>',$req->id_engineer)
			->get();

		foreach ($applyer_rejects as $applyer_reject) {
			$applyer_reject->status = "Reject";
			$applyer_reject->save();
		}


		return $history;
	}

	public function postReviewedByModerator(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 7;
		$history->date_time = Carbon::now()->toDateTimeString();
		// $history->detail_activity = "Moderator accept " + ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) + " Apply";
		$history->detail_activity = "Jobs has been reviewed";
		$history->save();
	}

	public function postFinishedByModerator(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 8;
		$history->date_time = Carbon::now()->toDateTimeString();
		// $history->detail_activity = "Moderator accept " + ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) + " Apply";
		$history->detail_activity = "Jobs has beed confirm by customer";
		$history->save();
	}

	public function postPayedByModerator(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 9;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Moderator make payment";
		$history->save();

		$payment = new Payment();
		$payment->id_job = $req->id_job;
		$payment->id_history = $history->id;
		$payment->payment_to = Job::find($req->id_job)->working_engineer[0]->id_engineer;
		$payment->payment_from = $req->id_moderator;
		$payment->payment_nominal = $req->nominal;
		$payment->payment_method = "Bank Transfer";
		$payment->payment_invoice = "";
		$payment->date_add = Carbon::now()->toDateTimeString();
		$payment->save();

		$payment_history = new Payment_history();
		$payment_history->id_payment = $payment->id;
		$payment_history->id_user = $req->id_moderator;
		$payment_history->date_time = Carbon::now()->toDateTimeString();
		$payment_history->activity = "Make Payment";
		$payment_history->note = "Payment Proses";
		$payment_history->created_at = Carbon::now()->toDateTimeString();
		$payment_history->save();

		return $payment->id;
	}

	public function postPayedByModeratorInvoice(Request $req){
		$invoice = $req->file('invoice');

		$payment_history = Payment::find($req->id_payment);
		$payment_history->payment_invoice = "storage/image/payment_invoice/" . $invoice->getClientOriginalName();
		$payment_history->save();

		$invoice->move("storage/image/payment_invoice/",$invoice->getClientOriginalName());

		$payment_history = new Payment_history();
		$payment_history->id_payment = $req->id_payment;
		$payment_history->id_user = $req->id_moderator;
		$payment_history->date_time = Carbon::now()->toDateTimeString();
		$payment_history->activity = "Update Payment";
		$payment_history->note = "Payment has been sent";
		$payment_history->created_at = Carbon::now()->toDateTimeString();
		$payment_history->save();

		// return $payment_history;
	}

	public function getParameterClientAll(){
		return array("results" => Customer::select(DB::raw('`id`,`customer_name` AS `text`'))->get()->toArray());
	}

	public function getParameterLocationAll(Request $req){
		if($req->level == 2){
			return array("results" => Location::select('id','location_name')->where('level',2)->where('sub_location',$req->region)->get());
		} else if ($req->level == 3){
			return array("results" => Location::select('id','location_name')->where('level',3)->where('sub_location',$req->area)->get());
		} else {
			return array("results" => Location::select('id','location_name')->where('level',1)->get());
		}
	}

	public function getParameterPicAll(){
		return array("results" => Job_pic::select('id',DB::raw('CONCAT(`pic_name`," (",`pic_phone` ,")") AS `text`'))->get()->toArray());
	}

	public function getParameterLevelAll(){
		return array("results" => Job_level::select('id',DB::raw('CONCAT(`level_name`," - ",`level_description`) AS `text`'))->get()->toArray());
	}

	public function getParameterCategoryAll(){
		return array("results" => Job_category::select(DB::raw("`id`,`category_name` AS `text`"))->get());
	}

	public function getParameterFinalize(Request $req){
		return array(
			"location" => Location::find($req->id_location)->long_location,
			"pic" => Job_pic::find($req->id_pic)->pic_name . " [" . Job_pic::find($req->id_pic)->pic_phone . "]",
			"pic_email" => Job_pic::find($req->id_pic)->pic_mail,
			"category" => Job_category::find($req->id_category)->text_category,
		);
	}

	public function postPublishJobs(Request $req){
		$job = new Job();
		$job->id_category = $req->id_category;
		$job->id_customer = $req->id_client;
		$job->id_level = $req->id_level;
		$job->id_location = $req->id_location;
		$job->id_pic = $req->id_pic;
		$job->job_name = $req->job_title;
		$job->job_description = $req->job_description;
		$job->job_requrment = $req->job_requrement;
		$job->job_location = $req->job_address;
		$job->job_status = "Open";
		$job->job_price = $req->job_payment_base;
		$job->date_start = $req->job_duration_start . " 00:00:00.000000";
		$job->date_end = $req->job_duration_start . " 00:00:00.000000";
		$job->date_add = Carbon::now()->toDateTimeString();

		$job->save();

		$job_history = new Job_history();
		$job_history->id_job = $job->id;
		$job_history->id_user = $req->id_user;
		$job_history->id_activity = 1;
		$job_history->date_time = Carbon::now()->toDateTimeString();;
		$job_history->detail_activity = "Moderator Open Job";

		$job_history->save();
		
		return "Success";
	}

	public function sendNotification($to = "moderator@sinergy.co.id",$from = "agastya@sinergy.co.id",$title = "a",$message = "b",$id_history = 0,$id_job = 1){
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../eod-dev-key.json');
		$firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->withDatabaseUri(env('FIREBASE_DATABASEURL'))
			->create();

		$refrence = 'notification/web-notif/';

		$database = $firebase->getDatabase();

		$instanceDatabase = $database->getReference($refrence);

		$updateDatabase = $database
			->getReference($refrence . sizeof($instanceDatabase->getValue()))
			// ->getReference($refrence . 0)
			->set([
				"to" => $to,
				"from" => $from,
				"title" => $title,
				"message" => $message,
				"showed" => "false",
				"status" => "unread",
				"date_time" => Carbon::now()->timestamp,
				"history" => $id_history,
				"job" => $id_job
			]);
	}

	public function getJobReportPDF(Request $req){
		return response()->file(str_replace("public", "storage", Storage::disk('local')->allFiles('public/data/56_Backend_20_documentation/job_report/')[0]));
	}

}
