<?php

namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             =>$this->agent?->user->id,
            'name'           =>$this->agent?->user->name,
            'phone_number'   =>$this->agent?->phone_number,
        ];
    }
}
