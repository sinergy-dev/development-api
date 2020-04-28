<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engineer_location extends Model
{
    //
    protected $connection= 'mysql_dispatcher';

	protected $table = 'engineer_location';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_engineer',
		'id_location',
		'date_add'
	];
}
