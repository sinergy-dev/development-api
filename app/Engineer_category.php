<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engineer_category extends Model
{
    //
    protected $connection= 'mysql_dispatcher';

	protected $table = 'engineer_category';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_engineer',
		'id_category',
		'date_add'
	];
}
