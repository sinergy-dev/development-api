<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'payment';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_history',
		'payment_to',
		'payment_from',
		'payment_nominal',
		'payment_method',
		'payment_invoice',
		'date_add'
	];

	public function progress(){
		return $this->hasMany('App\Payment_history','id_payment','id');
	}
}
