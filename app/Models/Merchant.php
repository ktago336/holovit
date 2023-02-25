<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Merchant extends Authenticatable implements JWTSubject
{
    use Sortable;
    //
    public $sortable = ['id','business_type', 'busineess_name','store_name','contact','dob','gender','password','profile_image','address','city_name' , 'country_id', 'state_id', 'city_id','zipcode','forget_password_status','slug','status','user_status','activation_status','last_login','created_at','updated_at','google_id','facebook_id','linkedin_id','unique_key','description','device_id','is_verified','source_of_info_about_us', 'service_id', 'name', 'email_address', 'mobile_number', 'is_mobile_verified', 'otp','order_id', 'wallet_balance','total_earned_amount'];

 
	public function BusinessType()
	{
		return $this->belongsTo('App\Models\Category', 'business_type');
	}
	
	
	public function currentDeal()
	{
		return $this->hasOne('App\Models\Deal', 'merchant_id', 'id')->where(['status' => 1])
		->whereDate('expire_date', '>=', date('Y-m-d'))->orderBy('final_price');
	}
	public function City()
	{
		return $this->belongsTo('App\Models\Citie', 'city_id');
	}
	public function Country()
	{
		return $this->belongsTo('App\Models\Country', 'country_id');
	}
	public function State()
	{
		return $this->belongsTo('App\Models\State', 'state_id');
	}
	public function Locality()
	{
		return $this->belongsTo('App\Models\Locality', 'locality_id');
	}
	public function allDeal()
	{
		return $this->hasMany('App\Models\Deal', 'merchant_id', 'id')->where(['status' => 1])
		->whereDate('expire_date', '>=', date('Y-m-d'))->orderBy('id', 'DESC');
	}
	public function allServices($idd)
	{
		return $this->belongsTo('App\Models\Service', 'service_ids')->whereIn('Service.id', $idd);
	}
	public function allOrder()
	{
		return $this->hasMany('App\Models\Order', 'merchant_id', 'id')->where(['order_status' => 1]);
	}

	 public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }


    
}
