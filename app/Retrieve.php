<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retrieve extends Model
{
    protected $fillable = ['item_id', 'user_id', 'reason', 'store_id', 'admin_seen', 'seen', 'refund_number'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }

    public function item() {
        return $this->belongsTo('App\OrderItem', 'item_id');
    }
}