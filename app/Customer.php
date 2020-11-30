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

	protected $appends = [
		'location_client',
	];
	
	protected $fillable = [
		'customer_name',
		'customer_acronym',
		'customer_description',
		'date_add',
		'location',
		'address'
	];
	
	public function getLocationClientAttribute(){
		return Location::where('id',$this->location)->first();
	}

	public function pic(){
		return $this->hasMany('App\Job_pic','id_customer','id');
	}
}
