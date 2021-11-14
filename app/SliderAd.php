<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SliderAd extends Model
{
    protected $fillable = ['ad_id', 'slider_id'];

    protected $hidden = ['pivot'];
}
