<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'image',
        'store_id',
        'type',     // 1 => inside app
                    // 2 => ouside app
        'content',
        'place',    // 1 => slider
                    // 2 => normal ad
                    
        'content_type'  // 1 => product
                        // 2 => category
                        // 3 => store
    ];

    protected $hidden = ['pivot'];

    public function product() {
        return $this->belongsTo('App\Product', 'content');
    }

    public function category() {
        return $this->belongsTo('App\Category', 'content');
    }

    public function store() {
        return $this->belongsTo('App\Shop', 'content');
    }

    public function storeCat() {
        return $this->belongsTo('App\Shop', 'store_id');
    }

    public function text() {
        return $this->hasOne('App\SlideradText', 'ad_id');
    }
}
