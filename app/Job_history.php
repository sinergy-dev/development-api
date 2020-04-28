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
}
