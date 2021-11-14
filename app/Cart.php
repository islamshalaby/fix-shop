<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['visitor_id', 'web_visitor_id', 'product_id', 'count'];

    public function product() {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
