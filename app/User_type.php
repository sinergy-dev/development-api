<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'users_type';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'description'
	];
}
