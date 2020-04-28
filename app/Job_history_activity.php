<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_history_activity extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_history_activity';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'activity_name',
		'activity_description',
		'date_add'
	];
}
