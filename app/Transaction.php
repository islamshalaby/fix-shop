<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['like_order_id', 'price', 'product', 'like_product_id', 'date', 'like_serial_id', 'serial_code', 'serial_number', 'valid_to', 'product_id'];
}