<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'  =>$this->name,
            'link'  =>$this->link,
            'image' => $this->getFirstMediaUrl('images'),
            'created_at'  =>Carbon::parse($this->created_at)->format('d, M Y'),
        ];
    }
}
