<?php namespace App\Models;
use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Models\Contacts;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
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
	
	protected static function boot()
	{
		parent::boot();

		static::deleting(function($telco) {
			$relationMethods = ['listing', 'orders', 'subscriptions'];

			foreach ($relationMethods as $relationMethod) {
				if ($telco->$relationMethod()->count() > 0) {
					return false;
				}
			}
		});
	}

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
        if ($this->hasRole('user')) {
            $url = route('user.home');
        } else if ($this->hasRole('merchant')) {
            $url = route('merchant.home');
        } else {
            $url = route('admin.home');
        }

        return $url;
    }
	
	 public function get_user($id)
    {
       
          return $access_modules = DB::table('users')
			 ->where('id', $id)	 
			 ->first();   
	  
    }
	
	public function listing_reviews()
	{
		return $this->hasMany('App\Models\ListingReview', 'user_id');
	}
	
	public function listing()
    {
        return $this->hasOne('App\Models\Listing', 'user_id');
    }
	
     public function orders(){
        return $this->hasMany('App\Models\OrderBooking', 'user_id');
    }
	
	public function subscribed_category()
	{
		return $this->belongsTo('App\Models\Category', 'category_id');
	}
	
	public function last_subscription()
	{
		return $this->belongsTo('App\Models\VendorSubscription', 'last_subscription_id')->withDefault();
	}
	
    public function subscriptions(){
        return $this->hasMany('App\Models\VendorSubscription', 'merchant_id');
    }
	
	public function _addContact($user_id, $request, $return)
	{
		
		$validator = \Validator::make($request->all(),
			[
			'listing_id'                 => 'required|numeric'
			]
		);
		
		if ($validator->fails()) {    
		
			$return['msg'] = $validator->messages();	
			
		} else {
		
			$listing_id = $request->listing_id;
			
			$contact_exist = Contacts::where(['user_id' => $user_id, 'listing_id' => $listing_id])->first();
			
			if($contact_exist) {
				$return['msg'] = 'Selected listing is already found in your contacts.';
				$return['status'] = 2;
			} else {
				$Contacts=new Contacts;
				$Contacts->user_id=$user_id;
				$Contacts->listing_id=$listing_id;
				$Contacts->save();
				
				$return['msg'] = 'Selected listing added in your contacts.';
				$return['status'] = 1;
			}
		}
		
		return $return;
	}
	
}
