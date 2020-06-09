<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Location extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'location';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'long_location',
		'text'
	];
	
	protected $visible = [
		'id',
		'location_name',
		'sub_location',
		'date_add',
		'level',
		'long_location',
		'text'
	];

	protected $fillable = [
		'location_name',
		'sub_location',
		'level',
		'date_add'
		// 'long_location'
	];

	public function scopeLongLocation(){
		return $this->select('location3.id',DB::raw('CONCAT(`location3`.`location_name`, " - ", `location2`.`location_name`, " - ", `location`.`location_name` ) AS `location`'))
			->join(DB::raw('`location` `location2`'),'location2.sub_location','location.id')
			->join(DB::raw('`location` `location3`'),'location3.sub_location','location2.id');
	}

	public function getTextAttribute(){
		return $this->location_name;
	}

	public function getLongLocationAttribute(){
		$query = DB::connection('mysql_dispatcher')->table('location')->select('location3.id',DB::raw('CONCAT(`location3`.`location_name`, " - ", `location2`.`location_name`, " - ", `location`.`location_name` ) AS `location`'))
			->join(DB::raw('`location` `location2`'),'location2.sub_location','location.id')
			->join(DB::raw('`location` `location3`'),'location3.sub_location','location2.id')
			->where('location3.id','=',$this->id)->first();
		if($query == NULL){
			return "High Level Location";
		} else {
			return $query->location;
		}
		// return DB::connection('mysql_dispatcher')->table('location')->select('location3.id',DB::raw('CONCAT(`location3`.`location_name`, " - ", `location2`.`location_name`, " - ", `location`.`location_name` ) AS `location`'))
		// 	->join(DB::raw('`location` `location2`'),'location2.sub_location','location.id')
		// 	->join(DB::raw('`location` `location3`'),'location3.sub_location','location2.id')
		// 	->where('location3.id','=',$this->id)->first()->location;
		// return "{$this->location_name} {$this->location_name}";
	}
}
