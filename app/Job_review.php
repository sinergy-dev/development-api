<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Job_review extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_review';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	// protected $appends = [
	// 	'working_engineer',
	// 	'single_apply_engineer',
	// ];
	
	protected $fillable = [
		'id_job',
		'id_history',
		'job_summary',
		'job_rootcause',
		'job_countermeasure',
		'job_documentation',
		'job_report',
		'job_bast',
		'date_add'
	];

	// protected $visible = [
	// 	'id_category',
	// 	'id_customer',
	// 	'id_level',
	// 	'id_location',
	// 	'id_pic',
	// 	'job_name',
	// 	'job_description',
	// 	'job_requrment',
	// 	'job_location',
	// 	'date_start',
	// 	'date_end',
	// 	'working_engineer'
	// ];

}
