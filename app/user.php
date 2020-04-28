<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'user';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_type',
		'name',
		'password',
		'email',
		'address'
	];
}
