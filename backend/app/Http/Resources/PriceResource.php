<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'askingPrice' => floatval($this->asking_price),
            'sellingPrice' => floatval($this->selling_price)
        ];
    }
}