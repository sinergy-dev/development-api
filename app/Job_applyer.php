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

	public function user(){
		return $this->hasOne('App\Users','id','id_engineer');
	}

	public function working_engineer(){
		return $this->hasOne('App\Users','id','id_engineer');
	}
}
