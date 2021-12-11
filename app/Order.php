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
        'delivery_cost', // delivery & installation cost
        'total_price', 
        'status',   // 1 => in progress
                    // 2 => delivered
        'order_number',
        'follow_number',
        'discount',
        'expected_period',
        'count'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function user_data() {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'name');
    }

    public function address() {
        return $this->belongsTo('App\UserAddress', 'address_id');
    }

    public function items() {
        return $this->belongsToMany('App\Product', 'order_items', 'order_id', 'product_id')->select('*');
    }

    public function boughtOrders() {
        return $this->hasMany('App\OrderItem', 'order_id')->select('id', 'product_id', 'final_price', 'count');
    }

    public function orders() {
        $lang = session('language');
        $orders = $this->boughtOrders()->get()->makeHidden('product');
        
        $orders->map(function ($row) use ($lang) {
            
            if ($lang == 'en') {
                $row->product_name = $row->product->title_en;
            }else {
                $row->product_name = $row->product->title_ar;
            }
            
            if ($row->product->main_image) {
                
                $row->main_image = $row->product->main_image->image;
            }else {
                $row->main_image = "";
                if ($row->product->images) {
                    $row->main_image = $row->product->images[0]->image;
                }
            }
        });

        return $orders;
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

}
