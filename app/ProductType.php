<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = ['type_en', 'type_ar'];

    public function products() {
        return $this->hasMany('App\Product', 'type');
    }

}