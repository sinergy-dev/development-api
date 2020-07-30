<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Job_category_main extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_category_main';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	// protected $visible = [
	// 	'category_main_name',
	// 	'date_add'
	// ];
	
	protected $fillable = [
		'category_main_name',
		'date_add'
	];

	public function category(){
		return $this->hasMany('App\Job_category','id_category_main','id');
	}
}
