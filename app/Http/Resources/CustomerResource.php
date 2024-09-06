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
            'first_name'    =>$this->first_name,
            'middle_name'   =>$this->middle_name,
            'last_name'     =>$this->last_name,
            'other_name'    =>$this->other_name,
            'maritial_status' =>$this->marital_status?->name,
            'phone_number'  =>$this->phone_number,
            'id_number'     =>$this->id_number,
            'email'         =>$this->email,
            'gender'        =>$this->gender?->name,
            'dob'           =>$this->dob,
            'region'        =>$this->region?->name,
            'district'      =>$this->district?->name,
            'ward'          =>$this->ward?->name,
            'street'        =>$this->street,
            'resident_since'=>$this->resident_since ?? "N/A",
            'image'         =>asset('storage').'/'.$this->image,
            'customer_id'   =>$this->uuid,
            'student'       =>new StudentResource($this->student),
        ];
    }
}
