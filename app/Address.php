<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['visitor_id', 'address_id', 'latitude', 'longitude'];

    public function area() {
        return $this->belongsTo('App\Area', 'address_id');
    }
}