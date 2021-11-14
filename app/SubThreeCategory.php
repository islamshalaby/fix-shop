<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubThreeCategory extends Model
{
    //
    protected $fillable = ['title_en', 'title_ar', 'image', 'deleted', 'sub_category_id'];


    public function category() {
        return $this->belongsTo('App\SubTwoCategory', 'sub_category_id');
    }

    public function subCategories() {
        return $this->hasMany('App\SubFourCategory', 'sub_category_id')->where('deleted', 0);
    }

    public function products() {
        return $this->hasMany('App\Product', 'sub_category_three_id')->where('deleted', 0)->where('hidden', 0);
    }
}