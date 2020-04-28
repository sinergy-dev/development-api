<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'user_type';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'description'
	];
}
