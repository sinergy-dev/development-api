<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_category extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_category';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'category_name',
		'category_description',
		'date_add'
	];
}
