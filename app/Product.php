<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'barcode',
        'stored_number',
        'title_en',
        'title_ar',
        'offer',
        'brief_en',
        'brief_ar',
        'description_ar', 
        'description_en', 
        'final_price', 
        'price_before_offer',
        'offer_percentage',
        'category_id',
        'brand_id',
        'sub_category_id',
        'sub_category_two_id',
        'sub_category_three_id',
        'sub_category_four_id',
        'sub_category_five_id',
        'deleted',
        'total_quatity',
        'remaining_quantity',
        'hidden',
        'multi_options',
        'sold_count',
        'refund_count',
        'store_id',
        'order_period',
        'video',
        'vat_percentage',
        'vat_value',
        'installation_cost',
        'product_id', // like card product id
        'like_card', // 0 => false | 1 => true
        'recent_offers',    // 1 => added to recent offers
        'choose_for_you',   // 1 => added to choose for you offers
        'reviewed', // 0 => reviewed
                    // 1 => under review
        'type'  // 1 => ready-made clothes
                // 2 => Tailoring clothes
    ];

    protected $hidden = ['pivot'];
    

    public function images() {
        return $this->hasMany('App\ProductImage', 'product_id');
    }

    public function mainImage() {
        return $this->hasOne('App\ProductImage')->where('main', 1);
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function brand() {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function subCategory() {
        return $this->belongsTo('App\SubCategory', 'sub_category_id');
    }

    public function subCategoryTwo() {
        return $this->belongsTo('App\SubTwoCategory', 'sub_category_two_id');
    }

    public function subCategoryThree() {
        return $this->belongsTo('App\SubThreeCategory', 'sub_category_three_id');
    }

    public function subCategoryFour() {
        return $this->belongsTo('App\SubFourCategory', 'sub_category_four_id');
    }

    public function subCategoryFive() {
        return $this->belongsTo('App\SubFiveCategory', 'sub_category_five_id');
    }

    public function options() {
        return $this->hasMany('App\ProductOption', 'product_id');
    }

    public function orderItems() {
        return $this->hasMany('App\OrderItem', 'product_id');
    }

    public function orders() {
        return $this->belongsToMany('App\Order', 'order_items', 'product_id','order_id')->withPivot('count');
    }

    public function properties() {
        return $this->belongsToMany('App\Option', 'product_properties', 'product_id', 'option_id');
    }

    public function propertiesEn() {
        return $this->belongsToMany('App\Option', 'product_properties', 'product_id', 'option_id')->select('options.id as option_id', 'options.title_en as title', 'product_properties.value_id');
    }

    public function propertiesAr() {
        return $this->belongsToMany('App\Option', 'product_properties', 'product_id', 'option_id')->select('options.id as option_id', 'options.title_ar as title', 'product_properties.value_id');
    }

    public function values() {
        return $this->belongsToMany('App\OptionValue', 'product_properties', 'product_id', 'value_id');
    }

    public function specValues() {
        return $this->belongsToMany('App\OptionValue', 'product_properties', 'product_id', 'value_id')->select('value_en as value', 'value_ar as value');
    }

    public function mOptions() {
        return $this->belongsToMany('App\MultiOption', 'product_multi_options', 'product_id', 'multi_option_id');
    }

    public function mOptionsValuesEn() {
        return $this->belongsToMany('App\MultiOptionValue', 'product_multi_options', 'product_id', 'multi_option_value_id')->select('value_en as value', 'multi_option_values.id as option_value_id');
    }

    public function mOptionsValuesAr() {
        return $this->belongsToMany('App\MultiOptionValue', 'product_multi_options', 'product_id', 'multi_option_value_id')->select('value_ar as value', 'multi_option_values.id as option_value_id');
    }

    public function multiOptions() {
        return $this->hasMany('App\ProductMultiOption', 'product_id');
    }

    public function multiOptionss() {
        return $this->hasMany('App\ProductMultiOption', 'product_id');
    }

    public function mOptionsWhere($id) {
        return $this->multiOptions()->with('multiOption', 'multiOptionValue')->where('product_multi_options.id', $id)->first();
    }

    public function productProperties() {
        return $this->hasMany('App\ProductProperty', 'product_id');
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }

    public function storeWithLogoNameOnly() {
        return $this->belongsTo('App\Shop', 'store_id')->select('id', 'name', 'logo');
    }

    // public function serials() {
    //     return $this->hasMany('App\Serial', 'product_id')->where('deleted', 0)->where('sold', 0);
    // }

    public function allSerials() {
        return $this->hasMany('App\Serial', 'product_id');
    }

    public function eaxistanceSerials() {
        return $this->hasMany('App\Serial', 'product_id')->where('deleted', 0)->where('sold', 0);
    }

    public function country() {
        return $this->hasOne('App\ProductCountry', 'product_id')->where('country_id', 66);
    }

    public function prices() {
        return $this->belongsToMany('App\Country', 'product_countries', 'product_id', 'country_id')->select('*');
    }

    public function vips() {
        return $this->belongsToMany('App\Vip', 'product_vips', 'product_id', 'vip_id')->select('*', 'vips.title_en as vip_title', 'vips.title_ar as vip_title', 'vips.id as vip_id');
    }

    public function vip($vip) {
        return $this->hasOne('App\ProductVip', 'product_id')->where('vip_id', $vip)->first();
    }
   
}
