<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'delivery_cost', 'place_id', 'formatted_address_en', 'formatted_address_ar', 'governorate_id', 'deleted'];

    public function stores() {
        return $this->belongsToMany('App\Shop', 'delivery_areas', 'area_id', 'store_id')->select("*", "delivery_areas.id as d_areas_id");
    }

    public function governorate() {
        return $this->belongsTo('App\Governorate', 'governorate_id');
    }
}
