<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'memberNumber' => $this->member_number,
            'phoneNumber' => $this->phone_number,
            'address' => $this->address,
            'city' => $this->city,
            'postalCode' => $this->postal_code,
            'role' => $this->role,
            'productlists' => ProductlistResource::collection($this->productlists),
            'listHistory' => ProductlistResource::collection($this->listHistory),
        ];
    }
}