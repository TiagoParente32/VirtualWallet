<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WalletResource;

class MovementResource extends JsonResource
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
            'transfer' => $this->transfer,
            'transfer_wallet' => new WalletResource($this->transfer_wallet),
            'type_payment' => $this->type_payment,
            'category' => $this->category,
            'date' => $this->date,
            'start_balance' => $this->start_balance,
            'end_balance' => $this->end_balance,
            'value' => $this->value,
            'description' => $this->description,
            'source_description' => $this->source_description,
            'mb_entity_code' => $this->mb_entity_code,
            'mb_payment_reference' => $this->mb_payment_reference,
            'iban' => $this->iban
        ];

        return parent::toArray($request);
    }
}
