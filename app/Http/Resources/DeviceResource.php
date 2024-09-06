<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'name' =>$this->name,
            'price' =>$this->price,
            'plan' =>$this->plan,
            'initial_deposit' =>$this->initial_deposit,
            'device_category' =>$this->device_category?->name,
        ];
    }
}
