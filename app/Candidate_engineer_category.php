<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job_category;

class Candidate_engineer_category extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer_category';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'job_engineer',
	];

	protected $fillable = [
		'id_candidate',
		'id_category',
		'date_add'
	];

	public function getJobEngineerAttribute(){
		$category = Job_category::where('id',$this->id_category)->first();
		return $category;
	}


}
