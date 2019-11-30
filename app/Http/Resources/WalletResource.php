<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'balance' => $this->balance,
            'email' => $this->email,
            'user' => $this->user
            //'movements' => MovementResource::collection($this->movements),
            //'movements' => $this->movements
        ];
    }
}
