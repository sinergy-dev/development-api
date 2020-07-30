<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_engineer_history extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer_history';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'id_candidate',
		'history_status',
		'history_user',
		'history_detail',
		'history_date'
	];

	public function engineer(){
		return $this->hasMany('App\Candidate_engineer','id','id_candidate');
	}
}
