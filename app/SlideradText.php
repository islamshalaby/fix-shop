<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlideradText extends Model
{
    protected $fillable = ['text1_en', 'text2_en', 'text3_en', 'text1_ar', 'text2_ar', 'text3_ar', 'ad_id', 'highlighted', 'price'];

    public function ad() {
        return $this->belongsTo('App\Ad', 'ad_id');
    }
}