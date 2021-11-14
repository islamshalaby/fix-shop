<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductCountry extends Model
{
    protected $fillable = ['product_id', 'country_id', 'price'];
}