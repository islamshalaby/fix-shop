<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $fillable = ['area_id', 'delivery_cost', 'min_order_cost', 'estimated_arrival_time', 'store_id'];

    public function area() {
        return $this->belongsTo('App\Area', 'area_id');
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }
}