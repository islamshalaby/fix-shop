<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['phone_code', 'country_code', 'country_name', 'currency_en', 'currency_ar', 'icon'];

}
