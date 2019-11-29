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
        return $this->hasMany('App\Movements');
    }

    public function transfer_wallet()
    {
        return $this->hasMany('App\Movements', 'transfer_wallet_id');
    }
}
