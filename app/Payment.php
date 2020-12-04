<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job_category;
use DB;
use Carbon\Carbon;

class Payment extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'payment';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'payment_invoice_URL',
		'job_category_image',
		'date_huminize',
		'lastest_progress'
	];
	
	protected $fillable = [
		'id_job',
		'id_history',
		'id_payment_account',
		'payment_to',
		'payment_from',
		'payment_nominal',
		'payment_method',
		'payment_invoice',
		'date_add'
	];

	public function getPaymentInvoiceURLAttribute(){
		return env('APP_URL') . "/" . $this->payment_invoice;
	}

	public function progress(){
		return $this->hasMany('App\Payment_history','id_payment','id');
	}

	// public function lastest_progress(){
	public function getLastestProgressAttribute(){
		return DB::connection('mysql_dispatcher')->table('payment_history')->where('id_payment',$this->id)->orderBy('id','DESC')->first();
		// return $this->hasOne('App\Payment_history','id_payment','id')->orderBy(DB::raw('`payment_historya`.`id`','DESCA'));

		// return $this->hasOne('App\Payment_history','id_payment','id')->latest();
	}

	public function job(){
		return $this->hasOne('App\Job','id','id_job');
	}

	public function getJobCategoryImageAttribute(){
		return Job_category::find($this->job->id_category)->category_image_url;
	}

	public function getDateHuminizeAttribute(){
		$date1 = Carbon::parse(DB::connection('mysql_dispatcher')->table('payment_history')->where('id_payment',$this->id)->orderBy('id','DESC')->first()->date_time);
		$date2 = Carbon::now();
		return str_replace("before","ago",$date1->diffForHumans($date2));
	}

}
