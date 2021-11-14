<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    protected $fillable = ['home_meta_en',
     'home_meta_ar',
      'home_title_en',
       'home_title_ar',
        'home_description_en',
        'home_description_ar',
        'contact_title_en',
        'contact_title_ar',
        'contact_description_en',
        'contact_description_ar',
        'about_title_en',
        'about_title_ar',
        'about_description_en',
        'about_description_ar',
        'categories_title_en',
        'categories_title_ar',
        'categories_description_en',
        'categories_description_ar',
        'products_title_en',
        'products_title_ar',
        'products_description_en',
        'products_description_ar',
        'offers_title_en',
        'offers_title_ar',
        'offers_description_en',
        'offers_description_ar',
        'orders_title_en',
        'orders_title_ar',
        'orders_description_en',
        'orders_description_ar',
        'cart_title_en',
        'cart_title_ar',
        'cart_description_en',
        'cart_description_ar',
        'conditions_title_en',
        'conditions_title_ar',
        'conditions_description_en',
        'conditions_description_ar',
        'privacy_title_en',
        'privacy_title_ar',
        'privacy_description_en',
        'privacy_description_ar'
    ];
}
