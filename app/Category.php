<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'type', 'name'
    ];

    public function movements()
    {
        return $this->hasMany('App\Movements');
    }
}
