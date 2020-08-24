<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_account extends Model
{
	//
	protected $connection= 'mysql_dispatcher';

	protected $table = 'payment_account';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_user',
		'account_name',
		'account_alias',
		'account_number'
	];

	public function user(){
		return $this->hasOne('App\User','id','id_user');
	}
}
