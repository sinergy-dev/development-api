<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Job_request_support extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_request_support';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	

	protected $appends = [
		'job_category',
		'picture_support_url',
		// 'single_apply_engineer',
	];
	
	protected $fillable = [
		'id_job',
		'id_history',
		'id_engineer',
		'problem_support',
		'resason_support',
		'picture_support',
		'status',
		'date_add'
	];

	public function job(){
		return $this->hasOne('App\Job','id','id_job');
	}

	public function engineer(){
		return $this->hasOne('App\Users','id','id_engineer');
	}

	public function getJobCategoryAttribute(){
		return Job_category::find($this->job->id_category);
	}

	public function getPictureSupportUrlAttribute(){
		return env('APP_URL') . "/" . $this->picture_support;
	}

}
