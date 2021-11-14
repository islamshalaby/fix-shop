<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['phone_code', 'country_code', 'country_name', 'currency_en', 'currency_ar'];

    public function country() {
        return $this->belongsTo('App\Country', 'country_code', 'country_code');
    }
}