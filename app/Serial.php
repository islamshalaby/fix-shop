<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Serial extends Model
{
    protected $dates = ['valid_to'];
    protected $fillable = [
        'product_id',
        'serial',
        'serial_number',
        'valid_to',
        'sold',
        'deleted',
        'like_product_id'
    ];
    
    

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}