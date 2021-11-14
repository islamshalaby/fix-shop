<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    protected $fillable = ['title_en', 'title_ar', 'price'];
}