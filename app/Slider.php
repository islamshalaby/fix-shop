<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'type', // 1 => home - top
                // 2 => categories
                // 3 => home - bottom
        'category_id'
    ];

    protected $hidden = ['pivot'];

    public function images() {
        return $this->hasMany('App\SliderImage', 'slider_id');
    }

    public function ads() {
        return $this->belongsToMany('App\Ad', 'slider_ads', 'slider_id', 'ad_id')->select('ads.id', 'image', 'type', 'content', 'content_type', 'store_id');
    }
}
