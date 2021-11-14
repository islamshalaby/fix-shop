<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchantCategory extends Model
{
    protected $fillable = [
        'category_parent_id',   // categoryParentId from api
        'image' // amazonImage from api
    ];

}