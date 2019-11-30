<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'email', 'balance'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function movements()
    {
        return $this->hasMany('App\Movement', 'wallet_id');
    }

    public function transfer_wallet()
    {
        return $this->hasMany('App\Movement', 'transfer_wallet_id');
    }
}
