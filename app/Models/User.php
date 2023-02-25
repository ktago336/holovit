<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Sortable;
    //
    public $sortable = ['id', 'user_type', 'first_name', 'last_name', 'contact', 'dob', 'gender', 'email_address', 'password', 'profile_image', 'service_ids', 'address', 'city', 'zipcode', 'forget_password_status', 'slug', 'status', 'user_status', 'activation_status', 'last_login', 'created_at', 'updated_at', 'google_id', 'facebook_id', 'linkedin_id', 'unique_key', 'description', 'device_id', 'is_verified'];

   public function BusinessType()
	{
		return $this->belongsTo('App\Models\BusinessType', 'business_type');
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
    
}
