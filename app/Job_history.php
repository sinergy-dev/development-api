<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_history extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_history';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_job',
		'id_user',
		'id_activity',
		'date_time',
		'date_add'
	];

	public function user(){
		return $this->hasOne('App\Users','id','id_user');
	}

	public function history_activity(){
		return $this->hasOne('App\Job_history_activity','id','id_activity');
	}

	public function request_item(){
		return $this->hasOne('App\Job_request_item','id_history','id');
	}

	public function request_support(){
		return $this->hasOne('App\Job_request_support','id_history','id');
	}
}
