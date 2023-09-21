<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount'            =>$this->amount,
            'payment_reference' =>$this->payment_reference,
            'status'            =>$this->status,
            'remarks'           =>$this->remarks,
            'payment_date'     =>date('d,M-Y',strtotime($this->payment_date))
        ];
    }
}
