<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\WalletResource;
use App\Wallet;



class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'email' => $this->email,
            'photo' => $this->photo,
            'active' => $this->active,
            'balance' => $this->wallet['balance'] >0 ? 1:0,
            //'nif' => $this->nif
        ];
    }
}
