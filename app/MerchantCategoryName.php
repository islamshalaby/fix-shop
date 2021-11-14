<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchantCategoryName extends Model
{
    protected $fillable = ['name', 'language_id', 'merchant_category_id'];
}