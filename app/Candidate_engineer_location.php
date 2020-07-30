<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_engineer_location extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'candidate_engineer_location';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'location_engineer',
	];

	protected $fillable = [
		'id_candidate',
		'id_area',
		'date_add'
	];

	public function getLocationEngineerAttribute(){
		return Location::where('id',$this->id_area)->get();
		// return $this->hasOne('App\Location','id','id_area');
	}
}
