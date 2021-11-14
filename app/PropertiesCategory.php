<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertiesCategory extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'deleted'];

    public function options() {
        return $this->hasMany('App\Option', 'property_category_id');
    }
}