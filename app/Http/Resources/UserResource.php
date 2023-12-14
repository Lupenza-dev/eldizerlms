<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'         =>$this->name,
            'email'        =>$this->email,
            'is_password_changed'          =>$this->is_password_changed,
            'total_amount'                 =>$this->loan_contracts->sum('loan_amount') ?? 0,
            'amount'                       =>$this->loan_contracts->sum('amount') ?? 0,
            'outstanding_amount' =>$this->loan_contracts->sum('outstanding_amount')?? 0,
            'payment_date'       =>$this->loan_contracts->first()->next_payment_date ?? "",   
            'loan_count'         =>$this->loan_contracts->count(),
            'customer'           =>new CustomerResource($this->customer),
            
        ];

      
    }
}
