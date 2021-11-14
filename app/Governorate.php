<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'deleted'];

    public function areas() {
        return $this->hasMany('App\Area', 'governorate_id');
    }
}
