<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'phone_verified_at',
        'password',
        'fcm_token',
        'verified',
        'remember_token',
        'active',
        'seen',
        'free_ads_count',
        'paid_ads_count',
        'vip_id',
        'image',
        'facebook_id',
        'google_id'
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

    public function addresses() {
        return $this->hasMany('App\UserAddress', 'user_id')->where('deleted', 0);
    }

    public function vip() {
        return $this->belongsTo('App\Vip', 'vip_id');
    }

    public function favorites() {
        return $this->hasMany('App\Favorite', 'user_id')->has('product', '>', 0);
    }
}