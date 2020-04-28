<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'location';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'location_name',
		'sub_location',
		'date_add'
	];
}
