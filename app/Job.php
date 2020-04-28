<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_category',
		'id_customer',
		'id_level',
		'id_location',
		'id_pic',
		'job_name',
		'job_description',
		'job_requrment',
		'job_location',
		'date_start',
		'date_end'
	];

	public function customer(){
		return $this->hasOne('App\Customer','id');
	}

	public function progress(){
		return $this->hasMany('App\Job_history','id_job','id');
	}
}
