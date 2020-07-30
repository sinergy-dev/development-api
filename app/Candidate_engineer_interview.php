<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_engineer_interview extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer_interview';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'id_candidate',
		'interview_date',
		'interview_media',
		'interview_link',
		'interview_result'
	];
}
