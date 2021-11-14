<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = [
        'name',
        'shop',
        'phone',
        'id_number',
        'instagram',
        'account_number',
        'front_image',
        'back_image',
        'details',
        'seen'
    ];
}