<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Merchants extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();
    }

    public function hasRole($name)
    {
        foreach($this->roles as $role)
        {
            if($role->name == $name) return true;
        }

        return false;
    }

    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }

    public function social()
    {
        return $this->hasMany('App\Models\Social');
    }

    public function homeUrl()
    {
        if ($this->hasRole('merchant')) {
            $url = route('merchant.home');
        } else {
            $url = route('admin.home');
        }

        return $url;
    }
	
	public function listing()
    {
        return $this->hasOne('App\Models\Listing', 'user_id');
    }
	
     public function orders(){
        return $this->hasMany('App\Models\Shoporders');
    }
	
	public function subscribed_category()
	{
		return $this->belongsTo('App\Models\Category', 'category_id');
	}
	
	public function last_subscription()
	{
		return $this->belongsTo('App\Models\VendorSubscription', 'last_subscription_id');
	}
	
    public function subscriptions(){
        return $this->hasMany('App\Models\VendorSubscription', 'merchant_id');
    }
	public function listing_review()
	{
		return $this->belongsTo('App\Models\ListingReview', 'r_id');
	}
}
