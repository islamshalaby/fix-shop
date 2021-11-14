<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'category_id', 'property_category_id'];
    protected $hidden = ['pivot', 'category_id', 'property_category_id', 'created_at', 'updated_at'];

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function propertiesCategory() {
        return $this->belongsTo('App\PropertiesCategory', 'property_category_id');
    }

    public function subCategories() {
        return $this->belongsToMany('App\SubCategory', 'options_sub_categories', 'option_id', 'sub_category_id');
    }

    public function categories() {
        return $this->belongsToMany('App\Category', 'options_categories', 'option_id', 'category_id');
    }

    public function values() {
        return $this->hasMany('App\OptionValue', 'option_id');
    }
}
