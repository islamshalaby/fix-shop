<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainOrder extends Model
{
    protected $fillable = [
        'user_id', 
        'address_id', 
        'payment_method', 
        'subtotal_price', 
        'delivery_cost', 
        'total_price', 
        'status',   // 1 => in progress
                    // 4 => canceled from user
                    // 3 => delivered
                    // 9 => canceled from admin
        'main_order_number'
    ];
    protected $dates = ['created_at'];
    public function orders() {
        return $this->hasMany('App\Order', 'main_id');
    }

    public function orders_with_select() {
        return $this->hasMany('App\Order', 'main_id')->select('id', 'subtotal_price', 'delivery_cost', 'total_price', 'order_number', 'store_id', 'main_id', 'status', 'created_at');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function address() {
        return $this->belongsTo('App\UserAddress', 'address_id');
    }

    public function canceledOrders() {
        return $this->hasMany('App\Order', 'main_id')->whereIn('status', [4, 9]);
    }

    public function deliveredOrders() {
        return $this->hasMany('App\Order', 'main_id')->where('status', 3);
    }
}