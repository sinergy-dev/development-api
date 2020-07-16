<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engineer_level extends Model
{
    //
    protected $connection= 'mysql_dispatcher';

	protected $table = 'engineer_level';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_engineer',
		'id_level',
		'date_add'
	];
}
