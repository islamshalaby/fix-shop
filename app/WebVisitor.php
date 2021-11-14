<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebVisitor extends Model
{
    protected $fillable = ['ip', 'country_code', 'user_id'];

    public function country() {
        return $this->belongsTo('App\Country', 'country_code', 'country_code');
    }

    public function carts() {
        return $this->hasMany('App\Cart', 'visitor_id')->pluck('product_id')->toArray();
    }
}