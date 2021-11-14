<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeDetail extends Model
{
    protected $fillable = ['tall', 'shoulder_width', 'chest', 'waist', 'buttocks', 'sleeve', 'details', 'order_id', 'product_id', 'cart_id'];
    
}