<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionSubCategory extends Model
{
    protected $fillable = ['option_id', 'sub_category_id'];
}