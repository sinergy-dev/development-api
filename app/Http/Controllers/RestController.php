<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Users;
use App\Job;
use App\Job_applyer;
use App\Job_history;
use App\Job_category;
use App\Job_level;
use App\Job_category_main;
use App\Job_pic;
use App\Job_letter;
use App\Job_review;
use App\Engineer_category;
use App\Payment;
use App\Payment_history;
use App\Payment_account;
use App\Engineer_location;
use App\Engineer_level;
use App\Customer;
use App\Location;
use Carbon\Carbon;
use App\Job_request_item;
use App\Job_request_support;
use App\Candidate_engineer;
use App\Candidate_engineer_location;
use App\Candidate_engineer_category;
use App\Candidate_engineer_history;
use App\Candidate_engineer_history_activity;
use App\Candidate_engineer_interview;
use Auth;
use Hash;
use PDF;
use App\Mail\JoinPartnerModerator;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

use Google\Auth\CredentialsLoader;
use GuzzleHttp\Client;
use Storage;
use File;

use Aws\S3\S3Client;
use Image;


class RestController extends Controller
{
	// Untuk di activity Dashboard
	// Ada 5 get function
	public function getDashboard(Request $req){
		return collect(['users' => Users::find($req->id_user)]);
	}

	public function getDashboardModerator(){
		$count = DB::connection('mysql_dispatcher')->table('job')->select('job_status',DB::raw('COUNT(*) AS `count`'))->groupBy('job_status')->orderBy('job_status','ASC')->get();

		$count_open = DB::connection('mysql_dispatcher')->table('job')
					->where('job_status','Open')->count();

		$count_ready = DB::connection('mysql_dispatcher')->table('job')
		->where('job_status','Ready')->count();

		$count_progress = DB::connection('mysql_dispatcher')->table('job')
					->where('job_status','Progress')->count();

		$count_done = DB::connection('mysql_dispatcher')->table('job')
					->where('job_status','Done')->count();

		// return $count_open . "," . $count_ready . ","  . $count_progress . "," . $count_done;

		if ($count_open == 0) {
			return collect([
				'open' => $count_open,
				'ready' => $count[1]->count,
				'progress' => $count[0]->count,
				'done' => $count[2]->count,
				'total' => $count[0]->count + $count[1]->count + $count[2]->count + $count_open,
			]);
		
		}else if ($count_ready == 0 ) {
			return collect([
				'open' => $count[1]->count,
				'ready' => $count_ready,
				'progress' => $count[2]->count,
				'done' => $count[0]->count,
				'total' => $count[0]->count + $count[1]->count + $count[2]->count + $count_ready,
			]);
		}else if ($count_ready == 0 && $count_progress == 0 && $count_done == 0) {
			return collect([
				'open' => $count[0]->count,
				'ready' => $count_ready,
				'progress' => $count_progress,
				'done' => $count_done,
				'total' => $count[0]->count + $count_progress + $count_ready + $count_ready,
			]);
		
		}else if ($count_progress == 0 && $count_done == 0) {
			return collect([
				'open' => $count[0]->count,
				'ready' => $count[1]->count,
				'progress' => $count_progress,
				'done' => $count_done,
				'total' => $count[0]->count + $count[1]->count + $count_ready + $count_ready,
			]);
		
		}else if ($count_progress == 0) {
			return collect([
				'open' => $count[0]->count,
				'ready' => $count[1]->count,
				'progress' => $count_progress,
				'done' => $count[2]->count,
				'total' => $count[0]->count + $count[1]->count + $count[2]->count + $count_progress,
			]);
		
		}else if ($count_done == 0) {
			return collect([
				'open' => $count[0]->count,
				'ready' => $count[1]->count,
				'progress' => $count[2]->count,
				'done' => $count_done,
				'total' => $count[0]->count + $count[1]->count + $count[2]->count + $count_done,
			]);
		
		}
	}

	public function getTopEngineer(){
		$count = DB::connection('mysql_dispatcher')->table('users')->join('job_applyer','job_applyer.id_engineer','=','users.id')->join('job','job.id','=','job_applyer.id_job')->select('users.name','users.date_of_join','photo',DB::raw('COUNT(`status`) AS count'),DB::raw('SUM(`job_price`) as job_price'))->where('status','Accept')->groupBy('job_applyer.id_engineer')->orderBy('count','DESC')->limit('4')->get();

		return $count;
	}

	public function getJobCategory(){
		return collect(['job_category' => Job_category::all()]);
	}

	public function getJobCategoryPaging(Request $req){
		return Job_category::paginate($req->per_page);
	}

	public function getJobCategoryAll(){
		return collect(['job_category_all' => Job_category_main::with('category')->get()]);
	}

	public function getJobCategoryMain(Request $req){
		$MainCategory = collect(Job_category_main::select(DB::raw('`id`,`category_main_name` AS `text`'))->orderBy('category_main_name','asc')->get());

		return array("results" => $MainCategory);
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

	public function getJobListAndSumaryPaginate(Request $req){
		// return Job::with(['customer','location','category'])->orderBy('id_job','DESC')->paginate($req->per_page);
		return Job::with(['customer','location','category'])
		->orderByRaw('FIELD(job_status,
        "Open",
        "Ready",
        "Progress",
        "Done")')
        ->paginate($req->per_page);
		
	}

	public function getJobListAndSumarySearch(Request $req){
		$query = Job::with(['customer','location','category'])->orderBy('id','DESC')->select("*");
		$searchFields = ['job_name','job_status','job_description','job_requrment'];
		$query->where(function($query) use($req, $searchFields){
			$customer_id = Customer::where('customer_name', 'LIKE', '%' . $req->search . '%')->pluck('id')->all();
			if(!empty($customer_id)){
				$query->orWhereRaw('`id_customer` IN (' . implode(",",$customer_id) . ")");
			}

			$customer_id = Job_category::where('category_name', 'LIKE', '%' . $req->search . '%')->pluck('id')->all();
			if(!empty($customer_id)){
				$query->orWhereRaw('`id_category` IN (' . implode(",",$customer_id) . ")");
			}

			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});
		return $query->paginate($req->per_page)->appends($req->only('search'));
	}

	public function getJobListAndSumaryFilterStatus(Request $req){
		$query = Job::with(['customer','location','category'])->orderBy('id','DESC')->select("*");
		$searchFields = ['job_status'];
		$query->where(function($query) use($req, $searchFields){
			$customer_id = Customer::where('customer_name', 'LIKE', '%' . $req->search . '%')->pluck('id')->all();
			if(!empty($customer_id)){
				$query->orWhereRaw('`id_customer` IN (' . implode(",",$customer_id) . ")");
			}

			$customer_id = Job_category::where('category_name', 'LIKE', '%' . $req->search . '%')->pluck('id')->all();
			if(!empty($customer_id)){
				$query->orWhereRaw('`id_category` IN (' . implode(",",$customer_id) . ")");
			}

			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});
		return $query->paginate($req->per_page)->appends($req->only('search'));
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

	public function getJobForLoAPDF(Request $req){
		$job = Job::with(['customer','pic','category','location','level'])->find($req->id_job);
		return collect([
			'job' => $job,
			'engineer' => Users::find($job->working_engineer->id_engineer),
			'last_job_letter' => Job_letter::orderBy('id','DESC')->first()->id
		]);
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
			'applyer' => Job_applyer::with('user')
				->where('id_job',$req->id_job)
				->orderBy('status','ASC')
				->get()
		]);
	}

	// Untuk function di bawah ini akan mengambil job
	// berserta array progress di dalamnya

	public function getJobProgress(Request $req){
		return collect([
			'job' => Job::with(['progress','customer','location','level','pic','category'])->find($req->id_job),
			'progress' => Job_history::with(['user','request_item','request_support'])->where('id_job',$req->id_job)->orderBy('date_time','DESC')->get()
		]);
	}

	public function getJobPayment(Request $req){
		return collect(['payment' => Payment::with(['progress','lastest_progress','job'])->where('payment_to',$req->id_engineer)->get()]);
	}

	public function getJobPaymentDetail(Request $req){
		return collect(['payment' => Payment::with(['progress','job'])->find($req->id_payment)]);
	}

	public function getJobByCategory(Request $req){
		// return collect(['job' => Job::with(['category','customer','location'])->get()]);
		return collect(['job' => Job::with(['category','customer','location'])->whereIn('id', Job_applyer::where('id_engineer','1')->pluck('id_job'))->get()]);
		
	}

	public function getEngineerList(Request $req){
		return Users::where('id_type',1)->paginate($req->per_page);
	}

	public function getEngineerListSearch(Request $req){
		$query = Users::orderBy('id','DESC')->select("*");
		$searchFields = ['name','email','phone','address'];
		$query->where(function($query) use($req, $searchFields){

			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});
		return $query->paginate($req->per_page)->appends($req->only('search'));
	}

	public function getClientList(Request $req){
		return Customer::paginate($req->per_page);
	}

	public function getClientListSearch(Request $req){
		$query = Customer::orderBy('id','DESC')->select("*");
		$searchFields = ['customer_name','address'];
		$query->where(function($query) use($req, $searchFields){
			$location_id = Location::where('location_name', 'LIKE', '%' . $req->search . '%')->pluck('id')->all();
			if(!empty($location_id)){
				$query->orWhereRaw('`location` IN (' . implode(",",$location_id) . ")");
			}


			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});
		return $query->paginate($req->per_page)->appends($req->only('search'));
	}

	public function getJobCategorySearch(Request $req){
		$query = Job_category::orderBy('id','DESC')->select("*");
		$searchFields = ['category_name','category_description'];
		$query->where(function($query) use($req, $searchFields){

			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});

		return $query->paginate($req->per_page)->appends($req->only('search'));
	}

	public function getRequestitem(Request $req){
		return Job_request_item::with(['user'])->where('id_history', $req->id_history)->get();
	}

	public function getNewPartnerList(Request $req){
		return Candidate_engineer::paginate($req->per_page);
	}

	public function getPartnerListSearch(Request $req){
		$query = Candidate_engineer::orderBy('id','DESC')->select("*");
		$searchFields = ['name','email','phone','address'];
		$query->where(function($query) use($req, $searchFields){

			$searchWildcard = '%' . $req->search . '%';
			foreach($searchFields as $field){
				$query->orWhere($field, 'LIKE', $searchWildcard);
			}
		});
		return $query->paginate($req->per_page)->appends($req->only('search'));
	}

	public function getNewPartnerSelectedList(Request $req){
		$selectedPartner = collect(Candidate_engineer::select(DB::raw('`id`,`name` AS `text`'))->where('status','OK Agreement')->get());
		// return $selectedPartner->where('last_history','!=',9);
		// $selectedPartner = $selectedPartner->filter(function($item){
		// 	return $item->id != 1;
		// });
		return array("results" => $selectedPartner);
	}

	public function getNewPartnerIdentifier(Request $req){
		return Candidate_engineer::with(['interview'])->where('identifier',$req->identifier)->first();
	}

	public function getDetailPartnerList(Request $req){
		return collect([
			'partner' => Candidate_engineer::with(['category','history','interview','location'])->find($req->id_candidate),
			'partner_progress' => Candidate_engineer_history::with(['engineer'])->where('id_candidate',$req->id_candidate)->orderBy('history_date','DESC')->get()
		]);
	}

	public function postCategory(Request $req){
		$cat_image = $req->file('cat_image');

		$category = new Job_category();
		$category->id_category_main 	= $req->id_category_main;
		$category->category_name 		= $req->category_name;
		$category->category_description	= $req->category_description;
		$category->category_image 		= "";
		$category->date_add				= Carbon::now()->toDateTimeString();
		$category->save();

		$name_format = str_replace(' ', '_', $req->category_name);

		$image_format = $name_format . "."  . explode(".", $cat_image->getClientOriginalName())[1];

		$cat_image->storeAs(
			"public/android/image/category_image/" , $image_format
		);

		$category->category_image = "storage/android/image/category_image/" . $image_format;
		$category->save();

		return $category;
	}

	public function postCategoryMain(Request $req){
		$category_main = new Job_category_main();
		$category_main->category_main_name = $req->category_main_name;
		$category_main->date_add = Carbon::now()->toDateTimeString();
		$category_main->save();

		return $category_main;
	}

	public function postUpdateCategory(Request $req){
		$cat_image = $req->file('cat_image');

		if ($cat_image != NULL) {
			$name_format = str_replace(' ', '_', $req->category_name);

			$image_format = $name_format . "."  . explode(".", $cat_image->getClientOriginalName())[1];

			$image_path = 'public/android/image/category_image/'.explode("/", Job_category::where('id',$req->id)->first()->category_image)[4];  // Value is not URL but directory file path

			if(Storage::exists($image_path)) {
				Storage::delete($image_path);

			    $cat_image->storeAs(
					"public/android/image/category_image/" , $image_format
				);
			}

			$category = Job_category::where('id',$req->id)->first();
			$category->id_category_main 	= $req->id_category_main;
			$category->category_name 		= $req->category_name;
			$category->category_description	= $req->category_description;
			$category->category_image 		= "storage/android/image/category_image/" . $image_format;
			$category->update();
		}else{
			$category = Job_category::where('id',$req->id)->first();
			$category->id_category_main 	= $req->id_category_main;
			$category->category_name 		= $req->category_name;
			$category->category_description	= $req->category_description;
			$category->update();
		}

		return $category;
	
	}


	//notif ke moderator

	public function postBasicJoin(Request $req){

		$files_ktp = $req->file('ktp_files');

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < 5; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

		$partner = new Candidate_engineer();
		$partner->name = $req->name;
		$partner->email = $req->email;
		$partner->phone = $req->phone;
		$partner->address = $req->address;
		$partner->ktp_nik = $req->ktp_nik;
		$partner->identifier = $randomString;
		$partner->ktp_files = "public/data/image.jpg";
		$partner->status = "On Progress";
		$partner->save();

		$ktp_name = "ktp_files_". Carbon::now()->timestamp. "." .explode(".", $files_ktp->getClientOriginalName())[1];

		$files_ktp->storeAs(
			"public/candidate_data/" . $partner->id  . "_" . $req->name . "/ktp/",
			$ktp_name
		);

		$partner->ktp_files = "storage/candidate_data/" . $partner->id  . "_" . $req->name . "/ktp/" .
			$ktp_name;
		$partner->save();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $partner->id;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$partner = Candidate_engineer::
				select('name','id','identifier','status')
				->where('id',$partner->id)->first();

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$partner->id)
				->orderBy('history_date','DESC')->get();

		Mail::to($req->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] You`ve been Success for filling First Stage'));

		$this->sendNotification(
			'moderator@sinergy.co.id',
			$req->email,
			$req->name . " [Reg - Basic Join]",
			$req->name . " has been register for, immediately do further checks",
			"On Progress",
			$partner->id
		);

		return $partner;

	}

	//email ke partner

	public function postSubmitPartner(Request $req){
		// $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	 //    $charactersLength = strlen($characters);
	 //    $randomString = '';
	 //    for ($i = 0; $i < 5; $i++) {
	 //        $randomString .= $characters[rand(0, $charactersLength - 1)];
	 //    }
		
		$submit = Candidate_engineer::where('id',$req->id_candidate)->first();
		$submit->status 	= $req->status;
		// $submit->identifier = $randomString;
		$submit->update();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $req->id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$partner = Candidate_engineer::with(['interview'])->
				select('name','id','identifier','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to(Candidate_engineer::where('id',$req->id_candidate)->first()->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Congrats! You`ve been Confirmed.'));

		return 'success';
	}

	//post join tidak butuh email tapi notif ke modeator

	public function postAdvancedJoin(Request $req){

		$files_pf = $req->file('portofolio_file');

		$id_candidate = Candidate_engineer::where('identifier',$req->identifier)->first()->id;

		$partner = Candidate_engineer::where('id',$id_candidate)->first();
		$partner->latest_education = $req->latest_education;
		// $partner->portofolio_file = $req->portofolio_file;
		$partner->status = "OK Basic";
		$partner->update();

		$partner_location = new Candidate_engineer_location();
		$partner_location->id_candidate = $id_candidate;
		$partner_location->id_area 		= $req->id_area;
		$partner_location->date_add 	= Carbon::now()->toDateTimeString();
		$partner_location->save();

		$cat = explode(",",$req->id_category);

		foreach ($cat as $data) {
			$partner_category = new Candidate_engineer_category();
			$partner_category->id_candidate = $id_candidate;
			$partner_category->id_category 	= $data;
			$partner_category->date_add 	= Carbon::now()->toDateTimeString();
			$partner_category->save();
		}

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$portofolio_name = "portofolio_files_". Carbon::now()->timestamp. "." .explode(".", $files_pf->getClientOriginalName())[1];

		$files_pf->storeAs(
			"public/candidate_data/" . $id_candidate  . "_" . Candidate_engineer::where('id',$id_candidate)->first()->name . "/portofolio/",
			$portofolio_name
		);

		$partner->portofolio_file = "storage/candidate_data/" . $id_candidate  . "_" . Candidate_engineer::where('id',$id_candidate)->first()->name . "/portofolio/" .
			$portofolio_name;

		$partner->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Candidate_engineer::find($id_candidate)->email,
			// Candidate_engineer::find('')$req->email,
			Candidate_engineer::find($id_candidate)->name . " [Reg - Advance Join]",
			Candidate_engineer::find($id_candidate)->name . " has been register for, immediately do further checks",
			"OK Basic",
			$partner->id
		);

		return 'success';

	}

	//email ke partner

	public function postScheduleInterview(Request $req){

		// $update = Candidate_engineer::where('id',$req->id_candidate)->first();
		// $update->status 	= $req->status;
		// $update->update();
		$link = json_decode($this->WebexPostApi($req->interview_date, Carbon::parse($req->interview_date)->addHour()->toDateTimeString()),true);

		$webLink = $link["webLink"];

		$submit = new Candidate_engineer_interview();
		$submit->id_candidate 		= $req->id_candidate;
		$submit->interview_date 	= $req->interview_date;
		$submit->interview_media 	= 'webex';
		// $submit->interview_link 	= $req->interview_link;
		$submit->interview_link 	= $webLink;
		$submit->status 			= $req->status_interview;
		$submit->save();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $req->id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$partner = Candidate_engineer::with(['interview'])->select('name','id','identifier','status','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to(Candidate_engineer::where('id',$req->id_candidate)->first()->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Congrats! Published Interview Schedule.'));

		return $partner;
	}

	public function WebexPostApi($start_date,$end_date){
		$client = new Client();
	    $url = "https://webexapis.com/v1/meetings";

	    $response =  $client->request('POST', $url, [
			'headers' => [
				'Content-Type'     => 'application/json',
				'Authorization'      => 'Bearer NWFmMjBlODEtMzU5Yy00NzBlLWExYWQtZDg4ZjRhN2JmY2Q3MTE2ZmFlNGUtMzMz_P0A1_0a3c49be-fce4-4450-8609-1ef1499b8df4' 
			],'json' => [
				  "title" => "Partner Interview Schedule",
				  "agenda" => "Partner Interview Schedule Agenda",
				  "password" => "3101",
				  "start" => $start_date,
				  "end" => $end_date,
				  "enabledAutoRecordMeeting" => true,
				  "allowAnyUserToBeCoHost" => true
				
			]
		]);

		return $response->getBody();
	}

	//email ke partner
	public function postStartInterview(Request $req){
		// $update = Candidate_engineer::where('id',$req->id_candidate)->first();
		// $update->status = $req->status;
		// $update->update();

		$update_interview = Candidate_engineer_interview::where('id_candidate',$req->id_candidate)->first();
		$update_interview->status = $req->status_interview;
		$update_interview->update();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $req->id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$partner = Candidate_engineer::with(['interview'])->select('name','id','identifier','status','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to(Candidate_engineer::where('id',$req->id_candidate)->first()->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Interviews room has been started!'));

		return $partner;
	}

	public function postResultInterview(Request $req){
		$update_interview = Candidate_engineer_interview::where('id_candidate',$req->id_candidate)->first();
		$update_interview->interview_result = $req->interview_result;
		$update_interview->update();

		// $history = new Candidate_engineer_history();
		// $history->id_candidate 		= $req->id_candidate;
		// $history->history_status 	= $req->history_status;
		// $history->history_user 		= $req->history_user;
		// $history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		// $history->history_date 		= Carbon::now()->toDateTimeString();
		// $history->save();

		$partner = Candidate_engineer::with(['interview'])->select('name','id','identifier','status','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to(Candidate_engineer::where('id',$req->id_candidate)->first()->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Congrats! Interview Result.'));


		return 'success';
	}	

	public function postAgreementInterview(Request $req){
		$update_interview = Candidate_engineer::where('id',$req->id_candidate)->first();
		$update_interview->status = $req->status;
		$update_interview->update();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $req->id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$partner = Candidate_engineer::with(['interview'])->select('name','id','identifier','status','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to(Candidate_engineer::where('id',$req->id_candidate)->first()->email)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Verifying Data Agreement!'));

		return 'success';
	}	

	public function postPartnerAgreement(Request $req){
		$update_interview = Candidate_engineer::where('id',$req->id_candidate)->first();
		$update_interview->status = $req->status;
		$update_interview->candidate_account_name 	= $req->account_name;
		$update_interview->candidate_account_number = $req->account_number;
		$update_interview->candidate_account_alias 	= $req->account_alias;
		$update_interview->update();

		$history = new Candidate_engineer_history();
		$history->id_candidate 		= $req->id_candidate;
		$history->history_status 	= $req->history_status;
		$history->history_user 		= $req->history_user;
		$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
		$history->history_date 		= Carbon::now()->toDateTimeString();
		$history->save();

		$this->sendNotification(
			'moderator@sinergy.co.id',
			Candidate_engineer::find($req->id_candidate)->email,
			Candidate_engineer::find($req->id_candidate)->name . " [Reg - Make New Account!]",
			Candidate_engineer::find($req->id_candidate)->name . " has been register for, immediately do further checks",
			"OK Agreement",
			Candidate_engineer::find($req->id_candidate)->id
		);

		return 'success';
	}

	public function postNewClient(Request $req){
		$client = new Customer();
		$client->customer_name 	= $req->customer_name;
		$client->location 		= $req->id_location;
		$client->date_add 		= Carbon::now()->toDateTimeString();
		$client->address 		= $req->address;
		$client->save();

		$job_pic = new Job_pic();
		$job_pic->pic_name 		= $req->pic_name;
		$job_pic->pic_phone 	= $req->phone;
		$job_pic->pic_mail    	= $req->email;
		$job_pic->date_add 		= Carbon::now()->toDateTimeString();
		$job_pic->save();

		return $client;
	}

	public function postStatusRequestItem(Request $req){
		if ($req->status == 'approve') {

			$request_item = Job_request_item::where('id_history',$req->id_history)->first();
			$request_item->status_item = 'Done';
			$request_item->update();

		}else{

			$request_item = Job_request_item::where('id_history',$req->id_history)->first();
			$request_item->status_item = 'Rejected';
			$request_item->update();
		}
		

		return 'Success';
	}

	public function postStatusRequestSupport(Request $req){
		if ($req->status == 'approve') {

			$request_support = Job_request_support::where('id',$req->id_support)->first();
			$request_support->status = 'Progress';
			$request_support->update();

		}else if ($req->status == 'done') {

			$request_support = Job_request_support::where('id',$req->id_support)->first();
			$request_support->status = 'Done';
			$request_support->update();

		}else{

			$request_support = Job_request_support::where('id',$req->id_support)->first();
			$request_support->status = 'Reject';
			$request_support->update();
		}
		

		return 'Success';
	}

	public function updateEngineerData(Request $req){
		$engineer = Users::where('id',$req->id)->first();
		$engineer->name 	= $req->name_eng;
		$engineer->email 	= $req->email_eng;
		$engineer->phone 	= $req->phone_eng;
		$engineer->address 	= $req->adress_eng;
		// $engineer->password = Hash::make("asdasdasd");
		$engineer->update();

		if ($req->id_location != null) {
			// $engineer_loc = new Engineer_location();
			// $engineer_loc->id_engineer = $req->id;
			// $engineer_loc->id_location = $req->id_location;
			// $engineer_loc->date_add    = Carbon::now()->toDateTimeString();
			// $engineer_loc->save();

			$engineer_loc = Engineer_location::where('id_engineer',$req->id)->first();
			$engineer_loc->id_location = $req->id_location;
			$engineer_loc->save();
		}

		$payment_acc = Payment_account::where('id_user',$req->id)->first();
		$payment_acc->account_name = $req->account_name;
		$payment_acc->account_number = $req->account_number;
		$payment_acc->update();
	}

	public function postNewEngineer(Request $req){
		$engineer = new Users();
		$engineer->id_type  		= $req->id_type;
		$engineer->name 			= $req->name_eng;
		$engineer->email 			= $req->email_eng;
		$engineer->address 			= $req->adress_eng;
		$engineer->nik 				= 524345987;
		$engineer->photo			= "";
		$engineer->pleace_of_birth 	= "";
		$engineer->date_of_birth 	= "2000-04-14";
		$engineer->phone 			= $req->phone_eng;
		$engineer->password = Hash::make("asdasdasd");
		$engineer->save();

		$engineer_loc = new Engineer_location();
		$engineer_loc->id_engineer = $engineer->id;
		$engineer_loc->id_location = $req->id_location;
		$engineer_loc->date_add    = Carbon::now()->toDateTimeString();
		$engineer_loc->save();

		$payment_acc = new Payment_account();
		$payment_acc->id_user 			= $engineer->id;
		$payment_acc->account_name 		= $req->account_name;
		$payment_acc->account_number 	= $req->account_number;
		$payment_acc->account_alias 	= $req->account_alias;
		$payment_acc->save();

		$Engineer_level = new Engineer_level();
		$Engineer_level->id_engineer 	= $engineer->id;
		$Engineer_level->id_level 	 	= 1;
		$Engineer_level->date_add 	 	= Carbon::now()->toDateTimeString();
		$Engineer_level->save();

		if ($req->id_candidate != "") {
			$Candidate_engineer = Candidate_engineer::where('id',$req->id_candidate)->first();
			$Candidate_engineer->status 	= 'OK Partner';
			$Candidate_engineer->update();

			$history = new Candidate_engineer_history();
			$history->id_candidate 		= $req->id_candidate;
			$history->history_status 	= $req->history_status;
			$history->history_user 		= $req->history_user;
			$history->history_detail 	= Candidate_engineer_history_activity::where('id',$req->history_status)->first()->activity_description;
			$history->history_date 		= Carbon::now()->toDateTimeString();
			$history->save();
		}

		$partner = Candidate_engineer::with(['interview'])->select('name','id','identifier','status','latest_education')
				->where('id',$req->id_candidate)->first();

		$randomString = Candidate_engineer::where('id',$req->id_candidate)->first()->identifier;

		$activity = Candidate_engineer_history::select('history_detail')->where('id_candidate',$req->id_candidate)
				->orderBy('history_date','DESC')->get();

		Mail::to($req->email_eng)->send(new JoinPartnerModerator($randomString,$partner,$activity,'[EOD-App] Congrats, This is your new account!'));
		
		return $engineer;
	}

	public function postJobApply(Request $req){
		$applyer = new Job_applyer();
		$applyer->id_job = $req->id_job;
		$applyer->id_engineer = $req->id_engineer;
		$applyer->status = "Pending";
		$applyer->date_add = Carbon::now()->toDateTimeString();
		$applyer->save();

		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_engineer;
		$history->id_activity = 2;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) . " Apply job";
		$history->save();

		// sendNotification(
		// 	'moderator@sinergy.co.id',
		// 	Users::find($req->id_engineer)->email,
		// 	ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) . " Apply job",
		// 	Job::find($req->id_job)->job_name . " has been applied for, immediately do further checks",
		// 	$history->id
		// );

		return $applyer;
	}

	public function postJobStart(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_engineer;
		$history->id_activity = 4;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = ucfirst(explode("@",Users::find($req->id_engineer)->email)[0]) . " Job started";
		$history->save();

		$start_job = Job::find($req->id_job);
		$start_job->job_status = "Progress";
		$start_job->save();

		return $history;
	}

	public function postJobUpdate(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_engineer;
		$history->id_activity = 5;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Update Day " . 
			(Carbon::parse(
				Job_history::where('id_user',$req->id_engineer)
					->where('id_activity',4)
					->where('id_job',$req->id_job)
					->first()
					->date_time
				)->diffInDays(Carbon::now()
			) + 1) . " - " . $req->detail_activity;
		$history->save();
		return $history;
	}

	public function postJobFinish(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_engineer;
		$history->id_activity = 6;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Finish jobs ready to review";
		$history->save();
		return $history;
	}

	public function postApplyerUpdate(Request $req){

		$job = Job::find($req->id_job);
		$job->job_status = "Ready";
		$job->save();

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

		$user = Users::where('id',$req->id_engineer)->select('name')->first();

		$jobs = Job::where('id',$req->id_job)->first();

		foreach ($applyer_rejects as $applyer_reject) {
			$applyer_reject->status = "Reject";
			$applyer_reject->save();
		}

		$this->getTokenToNotification($req->id_engineer,'Job Approvement','Congrats!! '.$user->name.', you got a new job ('.$jobs->job_name.')');

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

		$engineer_applyer = Job::find($req->id_job)->working_engineer->id_engineer;

		$this->getTokenToNotification($engineer_applyer,'Job Reviewed','Hei, your job has been reviewed!');
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

		$engineer_applyer = Job::find($req->id_job)->working_engineer->id_engineer;

		$this->getTokenToNotification($engineer_applyer,'Job Finished','Hei, your finished job has been confirmed!');
	}

	public function postPayedByModeratorFirst(Request $req){
		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 9;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Moderator make payment";
		$history->save();

		return collect(["account" => Payment_account::with(['user'])
			->where('id_user',Job::find($req->id_job)->working_engineer->id_engineer)
			->get()
		]);

		// $engineer_applyer = Job::find($req->id_job)->working_engineer->id_engineer;

		// $this->getTokenToNotification($engineer_applyer);
	}

	public function postPayedByModeratorSecond(Request $req){
		$payment = new Payment();
		$payment->id_job = $req->id_job;
		$payment->id_history = Job_history::where('id_job',$req->id_job)->get()->last()->id;
		$payment->id_payment_account = Payment_account::where('id_user',Job::find($req->id_job)->working_engineer->id_engineer)->first()->id;
		$payment->payment_to = Job::find($req->id_job)->working_engineer->id_engineer;
		$payment->payment_from = $req->id_moderator;
		$payment->payment_nominal = str_replace(",", "", $req->nominal);
		$payment->payment_method = "";
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

		// $engineer_applyer = Job::find($req->id_job)->working_engineer->id_engineer;

		// $this->getTokenToNotification($engineer_applyer);

		return $payment;
	}

	public function postPayedByModeratorInvoice(Request $req){
		// return $req->all();
		$invoice = $req->file('invoice');
		// return $req->hasFile('image') ? 'true' : 'false';
		$invoice->storeAs(
			"public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/invoice_image",
			$invoice->getClientOriginalName()
		);

		// return "a";

		$payment_history = Payment::find($req->id_payment);
		$payment_history->payment_invoice = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/invoice_image/" . $invoice->getClientOriginalName();
		$payment_history->save();

		// // $invoice->move("storage/image/payment_invoice/",$invoice->getClientOriginalName());

		$payment_history = new Payment_history();
		$payment_history->id_payment = $req->id_payment;
		$payment_history->id_user = $req->id_moderator;
		$payment_history->date_time = Carbon::now()->toDateTimeString();
		$payment_history->activity = "Update Payment";
		$payment_history->note = "Payment has been sent";
		$payment_history->created_at = Carbon::now()->toDateTimeString();
		$payment_history->save();

		$payment_history = new Payment_history();
		$payment_history->id_payment = $req->id_payment;
		$payment_history->id_user = Job::find($req->id_job)->working_engineer->id_engineer;
		$payment_history->date_time = Carbon::now()->toDateTimeString();
		$payment_history->activity = "Confirm Payment";
		$payment_history->note = "Payment has been confirm and recived";
		$payment_history->created_at = Carbon::now()->toDateTimeString();
		$payment_history->save();

		$history = new Job_history();
		$history->id_job = $req->id_job;
		$history->id_user = $req->id_moderator;
		$history->id_activity = 10;
		$history->date_time = Carbon::now()->toDateTimeString();
		$history->detail_activity = "Confirm payment has beed recived";
		$history->save();

		$job = Job::find($req->id_job);
		$job->job_status = "Done";
		$job->save();

		$engineer_applyer = Job::find($req->id_job)->working_engineer->id_engineer;

		$this->getTokenToNotification($engineer_applyer,'Job Payment','Hei, please checking your job payment!');

		return $payment_history;
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

		$all_applyer = Users::where('id_type',1)->get();

		// foreach ($all_applyer as $all_applyer) {
		// 	$this->getTokenToNotification($all_applyer->id,'New Job','Hei, There`re new job available!');
		// }
		
		return "Success";
	}

	public function postQRRecive(Request $req){
		$qr = $req->file('qr_image');

		$qr->storeAs(
			"public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/qr_image",
			$qr->getClientOriginalName()
		);

		// $qr->move("storage/image/job_qr/",$qr->getClientOriginalName());
	}

	public function postPDFRecive(Request $req){
		$pdf = $req->file('pdf_file');
		// $pdf->storeAs('asd','asd.pdf');
		$pdf->storeAs(
			"public/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/letter_of_assignment",
			$pdf->getClientOriginalName()
		);
	}

	public function postLetter(Request $req){
		$letter = new Job_letter();
		$letter->id_job = $req->id_job;
		$letter->no_letter = $req->no_letter;
		$letter->qr_file = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/qr_image/" . $req->qr_file;
		$letter->pdf_file = "storage/data/" . $req->id_job . "_" . str_replace(" ","_",Job::find($req->id_job)->job_name) . "_documentation/letter_of_assignment/" . $req->pdf_file;

		$letter->created_by = $req->created_by;
		$letter->date_add = Carbon::now()->toDateTimeString();
		$letter->save();
	}

	public function getTokenToNotification2(Request $req){
		$this->getTokenToNotification(
			$req->id,
			$req->x,
			$req->y
		);
	}

	// public function getTokenToNotification(Request $req){
	public function getTokenToNotification($to,$messagetitle,$messagebody){
		$scope = 'https://www.googleapis.com/auth/firebase.messaging';
		$credentials = CredentialsLoader::makeCredentials($scope, json_decode(file_get_contents(__DIR__ . '/eod-dev-firebase-adminsdk.json'), true));

		$url = env('FIREBASE_FCM_URL');
		$client = new Client();
		$user = Users::find($to);
		$token = $user->fcm_token;
		// return $token;
		$client->request('POST', $url, [
			'headers' => [
				'Content-Type'     => 'application/json',
				'Authorization'      => 'Bearer ' . $credentials->fetchAuthToken()['access_token']
			],'json' => [
				"message" => [
					"token" => $token,
					"data" => [
						"id_user" => strval($user->id),
						"fild2" => "asdfasdfasdfasdfasd",
					],
					"notification" => [
						"body" => $messagebody,
						"title" => $messagetitle,
						// "data" => "dsafasdfa"
					]

				]
			]
		]);
	}

	public function sendNotification($to = "moderator@sinergy.co.id",$from = "agastya@sinergy.co.id",$title = "a",$message = "b",$id_history = 0,$id_job = 1){
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/eod-dev-key.json');
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


	


	// public function sendNotification($to = "moderator@sinergy.co.id",$from = "agastya@sinergy.co.id",$title = "a",$message = "b",$id_history){
	// 	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/eod-dev-key.json');
	// 	$firebase = (new Factory)
	// 		->withServiceAccount($serviceAccount)
	// 		->withDatabaseUri(env('FIREBASE_DATABASEURL'))
	// 		->create();

	// 	$database = $firebase->getDatabase();

	// 	$instanceDatabase = $database
	// 		->getReference('notification/web-notification/');

	// 	$updateDatabase = $database
	// 		->getReference('notification/web-notification/' . sizeof($instanceDatabase->getValue()))
	// 		->set([
	// 			"id_history" => 12,
	// 			"to" => $to,
	// 			"from" => $from,
	// 			"title" => $title,
	// 			"message" => $message,
	// 			"showed" => false,
	// 			"status" => "unread",
	// 			"date_time" => Carbon::now()->timestamp
	// 		]);

	// 	// dd(sizeof($instanceDatabase->getValue()));
	// }

	public function testStorage(){
		// return response(Storage::disk('minio')->get('meme.jpg'))->header('Content-Type','image/jpeg');
		// return response(Storage::disk('minio')->get('meme.jpg'))->header('Content-Type','image/jpeg');
		// return Storage::disk('minio')->mimeType('meme.jpg');
		return Storage::disk('minio')->response('meme.jpg');
		// return Image::make(Storage::disk('minio')->get('meme.jpg'));
		// $s3 = new S3Client([
		// 	'version' => 'latest',
		// 	'region'  => 'us-east-1',
		// 	'endpoint' => 'http://minio.sinergy.co.id',
		// 	'use_path_style_endpoint' => true,
		// 	'scheme'  => 'http',
		// 	'credentials' => [
		// 		'key'    => env('MINIO_KEY_ID'),
		// 		'secret' => env('MINIO_ACCESS_KEY'),
		// 	],
		// ]);

		// $retrive = $s3->getObject([
		// 	'Bucket' => 'testing',
		// 	'Key'    => 'meme.jpg',
		// 	'SaveAs' => 'meme.jpg'
		// ]);

		// return response($retrive['Body']);

	}

	// public function testSQLMap(Request $req){
	// 	return DB::connection('mysql_dispatcher')->table("job")->whereRaw('`job_name` = "' . $req->where . '"')->get();
	// }

	// public function testSQLMap2(Request $req){
	// 	// return DB::connection('mysql_dispatcher')->table("job")->whereRaw('`job_name` = "' . $req->where . '"')->get();
	// 	return DB::connection('mysql_dispatcher')->table("job")->where('job_name',$req->where)->get();
	// } 

}
