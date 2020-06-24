<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
	protected $connection= 'mysql_dispatcher';

	protected $table = 'users';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_type',
		'name',
		'password',
		'email',
		'address',
		'remember_token',
		'email_verified_at',
		'api_token',
		'fcp_token',
		'created_at',
		'updated_at'
	];

	protected $appends = [
		'location_engineer',
		'payment_acc_engineer',
		'level_engineer',
		'photo_image_url'
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
		return "https://sinergy-dev.xyz/" . $this->photo;
	}
}
