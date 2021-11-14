<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Shop  extends  Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'logo',
        'cover',
        'email',
        'phone',
        'fcm_token',
        'password',
        'seller_id',
        'show_home',
        'min_order_cost',
        'status'    // 1 => active
    ];

    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
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

    protected $appends = ['custom'];
    public function getCustomAttribute()
    {
        $data['setting'] = Setting::where('id' ,1)->select('app_name_en' , 'app_name_ar' , 'logo')->first();
        return $data;
    }

    public function products() {
        return $this->hasMany('App\Product', 'store_id');
    }

    public function categories() {
        return $this->belongsToMany('App\Category', 'stores_categories', 'store_id', 'category_id');
    }

    public function seller() {
        return $this->belongsTo('App\Seller', 'seller_id');
    }

    public function areas() {
        return $this->hasMany('App\DeliveryArea', 'store_id');
    }

    public function deliveryByarea($area) {
        $data = $this->hasOne('App\DeliveryArea', 'store_id')->where('area_id', $area)->first();
        // dd($data);
        return $this->hasOne('App\DeliveryArea', 'store_id')->where('area_id', $area)->first();
    }
}