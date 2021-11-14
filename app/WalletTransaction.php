<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = ['value', 'user_id', 'wallet_id'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function wallet() {
        return $this->belongsTo('App\Wallet', 'wallet_id');
    }
}