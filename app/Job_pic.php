<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_pic extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_pic';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'pic_name',
		'pic_phone',
		'pic_mail',
		'pic_address',
		'date_add'
	];
}
