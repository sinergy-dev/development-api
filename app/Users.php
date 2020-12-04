<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
	protected $connection= 'mysql_dispatcher';

	protected $table = 'users';
	
	protected $primaryKey = 'id';
	
	// public $timestamps = false;
	
	protected $fillable = [
		'id_type',
		'name',
		'password',
		'phone',
		'email',
		'address',
		'remember_token',
		'email_verified_at',
		'api_token',
		'fcp_token',
		'date_of_join',
		// 'created_at',
		// 'updated_at'
	];

	protected $appends = [
		'location_engineer',
		'payment_acc_engineer',
		'level_engineer',
		'photo_image_url',
		'category_engineer',
		'job_engineer_count',
		'fee_engineer_count'
	];
	
	public function getLocationEngineerAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			return Location::where('id',Engineer_location::where('id_engineer',$this->id)->first()['id_location'])->first();
		} else {
			return "This user is moderator";
		}
	}

	public function getPaymentAccEngineerAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			return Payment_account::where('id_user',$this->id)->first();
		} else {
			return "This user is moderator";
		}
	}

	public function getLevelEngineerAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			return Job_level::find(Engineer_level::where('id_engineer',$this->id)->orderBy('id_level','DESC')->first()->id_level);
		} else {
			return "This user is moderator";
		}
	}

	public function getPhotoImageUrlAttribute() {
		// storage/image/user_photo/profile(1)-min.jpg
		// return env('APP_URL') . "/" . $this->photo;
		return env('API_LINK_CUSTOM2') . "/" . $this->photo;
	}

	public function getCategoryEngineerAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			return implode(", ",Job_category::whereIn('id',Engineer_category::where('id_engineer',$this->id)->get()->pluck('id'))->get()->pluck('category_name')->all());
		} else {
			return "This user is moderator";
		}
	}

	public function getJobEngineerCountAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			return Job_applyer::where('id_engineer',$this->id)->where('status','Accept')->count();
			// return Job_category::whereIn('id',Engineer_category::where('id_engineer',$this->id)->get()->pluck('id'))->get()->pluck('category_name');
		} else {
			return "This user is moderator";
		}
	}

	public function getFeeEngineerCountAttribute(){
		$id_type = $this->id_type;
		
		if($id_type == 1){
			// return Engineer_location:aaaaa:where(‘id_engineer’,$this->id)->first();
			// return array_sum([1,2,3,4]);
			return array_sum(Job::whereIn('id',Job_applyer::where('id_engineer',$this->id)->where('status','Accept')->pluck('id_job'))->where('job_status','Done')->pluck('job_price')->all());
			// return Job_category::whereIn('id',Engineer_category::where('id_engineer',$this->id)->get()->pluck('id'))->get()->pluck('category_name');
		} else {
			return "This user is moderator";
		}
	}
}
