<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_applyer extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_applyer';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_job',
		'id_engineer',
		'status',
		'date_add'
	];
}
