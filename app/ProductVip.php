<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductVip extends Model
{
    protected $fillable = ['vip_id', 'product_id', 'percentage'];
}