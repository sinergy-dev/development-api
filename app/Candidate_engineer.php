<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_engineer extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'last_history',
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'address',
		'location',
		'ktp_nik',
		'ktp_files',
		'latest_education',
		'portofolio_file',
		'identifier',
		'candidate_account_name',
		'candidate_account_number',
		'status',
	];
	
	public function category(){
		return $this->hasMany('App\Candidate_engineer_category','id_candidate');
	}

	public function history(){
		return $this->hasMany('App\Candidate_engineer_history','id_candidate');
	}

	public function interview(){
		return $this->hasOne('App\Candidate_engineer_interview','id_candidate');
	}

	public function location(){
		return $this->hasMany('App\Candidate_engineer_location','id_candidate');
		// return Candidate_engineer_location::where('id_candidate',$this->id)->first()->location_engineer->get();
	}

	public function getLastHistoryAttribute(){
		return Candidate_engineer_history::where('id_candidate',$this->id)->orderBy('history_date','DESC')->first()['history_status'];
	}
}
