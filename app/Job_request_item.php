<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Job_request_item extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_request_item';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	

	// protected $appends = [
	// 	'working_engineer',
	// 	'single_apply_engineer',
	// ];
	
	protected $fillable = [
		'id_job',
		'id_engineer',
		'name_item',
		'function_item',
		'documentation_item',
		'invoice_item',
		'status_item',
		'price_item',
		'documentation_success',
		'note_success ',
		'date_add'
	];

	public function job(){
		return $this->hasOne('App\Job','id','id_job');
	}

	public function engineer(){
		return $this->hasOne('App\Users','id','id_engineer');
	}



}
