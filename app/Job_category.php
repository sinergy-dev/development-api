<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Job_category extends Model
{
    protected $connection= 'mysql_dispatcher';

	protected $table = 'job_category';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $appends = [
		'category_image_url',
		'text',
		'text_category'
	];

	protected $visible = [
		'id',
		'id_category_main',
		'category_name',
		'category_description',
		'category_image',
		'category_image_url',
		'text',
		'text_category'
	];
	
	protected $fillable = [
		'id_category_main',
		'category_name',
		'category_description',
		'category_image',
		'date_add'
	];

	public function getCategoryImageUrlAttribute() {
		return env('APP_URL') . "/" . DB::connection('mysql_dispatcher')
			->table('job_category')
			->select('category_image')
			->where('id',$this->id)
			->first()
			->category_image;
	}

	public function getTextAttribute() {
		return str_limit(DB::connection('mysql_dispatcher')
			->table('job_category')
			->select(DB::raw('CONCAT("[",`category_name`,"] ",`category_description` ) as `category_name`'))
			->where('id',$this->id)
			->first()
			->category_name,100) . "...";
	}

	public function getTextCategoryAttribute() {
		return DB::connection('mysql_dispatcher')
			->table('job_category')
			->join('job_category_main','job_category_main.id','=','job_category.id_category_main')
			->select(DB::raw('CONCAT("[",`job_category_main`.`category_main_name`,"] ",`job_category`.`category_name` ) as `category_name`'))
			->where('job_category.id',$this->id)
			->first()
			->category_name;
	}

	public function category_main(){
		return $this->hasOne('App\Job_category_main','id','id_category_main');
	}
}
