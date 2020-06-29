<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Job extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'working_engineer',
		'single_apply_engineer',
		'latest_history',
	];
	
	protected $fillable = [
		'id_category',
		'id_customer',
		'id_level',
		'id_location',
		'id_pic',
		'job_name',
		'job_description',
		'job_requrment',
		'job_location',
		'job_status',
		'job_price',
		'date_start',
		'date_end',
		'date_add'
	];

	protected $perPage = 10;

	// protected $visible = [
	// 	'id_category',
	// 	'id_customer',
	// 	'id_level',
	// 	'id_location',
	// 	'id_pic',
	// 	'job_name',
	// 	'job_description',
	// 	'job_requrment',
	// 	'job_location',
	// 	'date_start',
	// 	'date_end',
	// 	'working_engineer'
	// ];

	public function customer(){
		return $this->hasOne('App\Customer','id','id_customer');
	}

	public function progress(){
		return $this->hasMany('App\Job_history','id_job','id');
	}

	public function category(){
		return $this->hasOne('App\Job_category','id','id_category');
	}

	public function location(){
		return $this->hasOne('App\Location','id','id_location');
	}

	public function scopeAddSelectLongLocation(){
		
		return $this->select('*')
		->join(DB::raw('(SELECT
					        `location3`.`id`,
					        CONCAT(
					            `location3`.`location_name`,
					            " - ",
					            `location2`.`location_name`,
					            " - ",
					            `location1`.`location_name`
					        ) AS `long_location`
					    FROM
					        `location` `location1`
					    INNER JOIN `location` `location2` ON
					        `location2`.`sub_location` = `location1`.`id`
					    INNER JOIN `location` `location3` ON
					        `location3`.`sub_location` = `location2`.`id`
					) `longLocation`'),'longLocation.id','=','job.id_location');

		}

	public function level(){
		return $this->hasOne('App\Job_level','id','id_level');
	}

	public function pic(){
		return $this->hasOne('App\Job_pic','id','id_pic');
	}

	public function apply_engineer(){
		return $this->hasMany('App\Job_applyer','id_job','id')->orderBy('status','ASC');
	}

	public function getWorkingEngineerAttribute(){
		$job = $this->apply_engineer;
		
		if($job->where('status',"Accept")->all()){
			return $job->where('status',"Accept")->first();
		} else {
			return "Engineer Not Selected";
		}
	}

	public function getLatestHistoryAttribute(){
		$history = Job_history::where('id_job',$this->id)->orderBy('date_time','DESC')->first();
		return collect(['history' => $history,'history_activity' => $history->history_activity->alternate_name,'user_name' => Users::find($history->id_user)->name]);
	}

	public function getSingleApplyEngineerAttribute(){
		return $this->apply_engineer;;
	}
}
