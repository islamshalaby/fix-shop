<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionCategory extends Model
{
    protected $fillable = ['option_id', 'category_id'];
}