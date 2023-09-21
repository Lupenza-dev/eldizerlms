<?php

namespace App\Http\Resources; 

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_name' =>$this->first_name.' '.$this->last_name,
            'phone_number'  =>$this->phone_number,
            'email'         =>$this->email,
            'gender'        =>$this->gender?->name,
            'dob'           =>$this->dob,
            'region'        =>$this->region?->name,
            'district'      =>$this->district?->name,
            'ward'          =>$this->ward?->name,
            'street'        =>$this->street,
            'customer_id'        =>$this->uuid,
        ];
    }
}
