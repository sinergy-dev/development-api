<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_level extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_level';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'level_name',
		'level_description',
		'date_add'
	];
}
