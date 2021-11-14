<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['image', 'product_id', 'main'];
    protected $hidden = ['product_id', 'id', 'created_at', 'updated_at'];

    public function product() {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
