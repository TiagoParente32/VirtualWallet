<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'transfer', 'transfer_movement_id', 'type_payment', 'iban',
        'mb_entity_code', 'mb_payment_reference', 'description', 'source_description',
        'date', 'start_balance', 'end_balance', 'value'
    ];

    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }

    public function transfer_wallet()
    {
        return $this->belongsTo('App\Wallet');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
