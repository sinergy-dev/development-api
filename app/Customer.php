<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'customer';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'customer_name',
		'customer_acronym',
		'customer_description',
		'date_add',
		'location',
		'address'
	];

	protected $appends = [
		'location_client',
	];
	
	public function getLocationClientAttribute(){
		return Location::where('id',$this->location)->first();
	}
}
