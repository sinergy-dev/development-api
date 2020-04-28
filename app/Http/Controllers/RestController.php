<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Job;
use App\Job_category;
use App\Engineer_category;
use App\Payment;

class RestController extends Controller
{
	// Untuk di activity Dashboard
	// Ada 5 get function
	public function getDashboard(Request $req){
		return Users::find($req->id_user);
	}

	public function getJobCategory(){
		return Job_category::all();
	}

	public function getJobList(){
		return Job::where('job_status','Open')->get();
	}

	public function getJobListSumary(Request $req){
		return Job::with('customer')->find($req->id_job);
	}

	public function getJobListRecomended(Request $req){
		return Job::whereIn(
			'id_category',
			Engineer_category::where('id_engineer',$req->id_engineer)
				->pluck('id_category')
				->all()
			)
		->get();
	}

	// Untuk di activity Job Detail dan Job Progress

	// Dalam function di bawah ini nanti untuk frontend
	// bisa di tambahkan untuk if condition `status` "Open"
	// Jika open maka akan muncul button cancel dan apply
	// Dan jika `status` = "Approved" maka akan muncul button start

	public function getJobOpen(Request $req){
		return Job::find($req->id_job);
	}

	// Untuk function di bawah ini akan mengambil job
	// berserta array progress di dalamnya

	public function getJobProgress(Request $req){
		return Job::with('progress')->find($req->id_job);
	}

	public function getJobPayment(Request $req){
		return Payment::where('payment_to',$req->id_engineer)->get();
	}

	public function getJobPaymentDetail(Request $req){
		return Payment::with('progress')->find($req->id_payment);
	}

}
