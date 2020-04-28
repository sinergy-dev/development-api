<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_history extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'payment_history';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_payment',
		'id_user',
		'date_time',
		'activity',
		'note'
	];
}
