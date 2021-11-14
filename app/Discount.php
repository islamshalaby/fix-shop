<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['value', 'min_products_number', 'max_products_number', 'deleted'];
}
