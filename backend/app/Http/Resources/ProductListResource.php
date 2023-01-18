<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $profit = 0;

        // If the products are loaded, calculate the profit
        if ($this->relationLoaded('products')) {
            foreach ($this->products as $product) {
                if ($product->is_sold) {
                    $profit += $product->price->asking_price;
                }
            }
        }

        return [
            'id' => $this->id,
            'listNumber' => $this->list_number,
            'memberNumber' => $this->member_number,
            'isUserConfirmed' => $this->is_user_confirmed ? true : false,
            'isEmployeeValidated' => $this->is_employee_validated ? true : false,
            'editionId' => $this->edition_id,
            'userId' => $this->user_id,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'user' => new UserResource($this->whenLoaded('user')),
            'edition' => new EditionResource($this->whenLoaded('edition')),
            'isPaidToUser' => $this->is_paid_to_user ? true : false,
            'userProfit' => $profit
        ];
    }
}