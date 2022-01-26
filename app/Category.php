<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $fillable = ['image', 'title_en', 'title_ar', 'deleted', 'show_home', 'sort'];
    protected $hidden = ['pivot'];
    protected  $appends = ['offers'];

    public function brands() {
        return $this->hasMany('App\Brand', 'category_id');
    }

    public function products() {
        return $this->hasMany('App\Product', 'category_id')->where('deleted', 0)->where('hidden', 0);
    }

    public function recent_products() {
        return $this->products()->where('created_at', ">", DB::raw('NOW() - INTERVAL 1 WEEK'));
    }

    public function options() {
        return $this->belongsToMany('App\Option', 'options_categories', 'category_id', 'option_id');
    }

    public function optionsAr() {
        return $this->belongsToMany('App\Option', 'options_categories', 'category_id', 'option_id')->select('options.id as option_id', 'options.title_ar as title');
    }

    public function optionsEn() {
        return $this->belongsToMany('App\Option', 'options_categories', 'category_id', 'option_id')->select('options.id as option_id', 'options.title_en as title');
    }

    public function multiOptions() {
        return $this->belongsToMany('App\MultiOption', 'multi_options_categories', 'category_id', 'multi_option_id');
    }

    public function optionsWithValues() {
        return $this->options()->with('values');
    }

    public function multiOptionsWithValues() {
        return $this->multiOptions()->with('values');
    }

    public function subCategories() {
        return $this->hasMany('App\SubCategory', 'category_id')->where('deleted', 0);
    }

    public function offersProducts() {
        $lang = session('language');
        return $this->hasMany('App\Product', 'category_id')->where('deleted', 0)->where('hidden', 0)->where('recent_offers', 1)
        ->select('id', 'title_' . $lang . ' as title', 'final_price', 'price_before_offer', 'offer_percentage', 'offer');
    }

    public function getOffersAttribute() {
        $products = $this->offersProducts;
        $products->makeHidden(['mainImage']);
        $products->map(function ($row) {
            $image = '';
            if ($row->mainImage) {
                $image = $row->mainImage->image;
            }
            $row->image = $image;
            return $row;
        });
        
        return $products;
    }
}
