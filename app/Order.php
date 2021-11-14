<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'address_id', 
        'payment_method', 
        'subtotal_price', 
        'delivery_cost', 
        'total_price', 
        'status',   // 1 => in progress
                    // 2 => delivery service
                    // 3 => delivered
                    // 4 => canceled from user
                    // 5 => refund request
                    // 6 => refund accepted
                    // 7 => refund refused
                    // 9 => canceled from admin
        'order_number',
        'store_id',
        'from_deliver_date',
        'to_deliver_date',
        'country_code',
        'main_id'
    ];

    protected $dates = ['from_deliver_date', 'to_deliver_date'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_data() {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'name');
    }

    public function address() {
        return $this->belongsTo('App\UserAddress', 'address_id');
    }

    public function main() {
        return $this->belongsTo('App\MainOrder', 'main_id');
    }

    public function main_order_data() {
        return $this->belongsTo('App\MainOrder', 'main_id')->select('id', 'main_order_number');
    }

    public function items() {
        return $this->belongsToMany('App\Product', 'order_items', 'order_id', 'product_id')->select('*');
    }

    public function products() {
        return $this->belongsToMany('App\Product', 'order_items', 'order_id', 'product_id')->select('*');
    }

    public function oItems() {
        return $this->hasMany('App\OrderItem', 'order_id');
    }

    public function canceledItems() {
        return $this->hasMany('App\OrderItem', 'order_id')->whereIn('status', [4, 9]);
    }

    public function deliveredOrders() {
        return $this->hasMany('App\OrderItem', 'order_id')->where('status', 3);
    }

    public function oItemsRefunded() {
        return $this->hasMany('App\OrderItem', 'order_id')->whereBetween('status', [5, 8]);
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }
}
