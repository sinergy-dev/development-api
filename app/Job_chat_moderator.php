<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_chat_moderator extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_chat_moderator';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'job_category'
	];
	
	protected $fillable = [
		'id_job',
		'id_engineer',
		'status',
		'date_add',
		'date_update'
	];

	public function job(){
		return $this->hasOne('App\Job','id','id_job');
	}

	public function getJobCategoryAttribute(){
		return Job_category::find($this->job->id_category);
	}
}