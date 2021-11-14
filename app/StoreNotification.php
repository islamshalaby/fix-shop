<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreNotification extends Model
{
    protected $fillable = ['title', 'body', 'image', 'store_id'];

    public function store() {
        return $this->belongsTo('App\Shop', 'store_id');
    }
}