<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubCategory extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'image', 'deleted', 'brand_id', 'category_id'];

    public function brand() {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function products() {
        return $this->hasMany('App\Product', 'sub_category_id')->where('deleted', 0)->where('hidden', 0);
    }

    public function recent_products() {
        return $this->products()->where('created_at', ">", DB::raw('NOW() - INTERVAL 1 WEEK'));
    }

    public function options() {
        return $this->belongsToMany('App\Option', 'options_sub_categories', 'sub_category_id', 'option_id');
    }

    public function multiOptions() {
        return $this->belongsToMany('App\MultiOption', 'multi_options_sub_categories', 'sub_category_id', 'multi_option_id');
    }

    public function optionsWithValues() {
        return $this->options()->with('values');
    }

    public function multiOptionsWithValues() {
        return $this->multiOptions()->with('values');
    }

    public function subCategories() {
        return $this->hasMany('App\SubTwoCategory', 'sub_category_id')->where('deleted', 0);
    }
}
