<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_letter extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_letter';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'no_letter',
		'qr_file',
		'pdf_file',
		'created_by',
		'date_add'
	];
}
