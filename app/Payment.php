<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job_category;

class Payment extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'payment';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'payment_invoice_URL',
		'job_category_image',
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

	public function lastest_progress(){
		return $this->hasOne('App\Payment_history','id_payment','id')->latest();
	}

	public function job(){
		return $this->hasOne('App\Job','id','id_job');
	}

	public function getJobCategoryImageAttribute(){
		return Job_category::find($this->job->id_category)->category_image_url;
	}

}
