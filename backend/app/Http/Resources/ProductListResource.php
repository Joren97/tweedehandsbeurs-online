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
        ];
    }
}