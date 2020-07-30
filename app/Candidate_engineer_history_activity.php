<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_engineer_history_activity extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer_history_activity';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'activity_name',
		'alternate_name',
		'activity_description'
	];
}
