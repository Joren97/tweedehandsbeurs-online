<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'priceId' => $this->price_id,

            'productlistId' => $this->productlist_id,
            'description' => $this->description,
            'isSold' => $this->is_sold == 1,
            'price' => PriceResource::make($this->price)
        ];
    }
}