<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer' =>new CustomerResource($this->customer),
            'amount'         =>$this->amount,
            'loan_amount'    =>$this->loan_amount,
            'plan'           =>$this->plan,
            'installment_amount'   =>$this->installment_amount,
            'interest_rate'        =>$this->interest_rate,
            'interest_amount'      =>$this->interest_amount,
            'fees_amount'          =>$this->fees_amount,
            'level'                =>$this->level,
            'loan_code'            =>$this->loan_code,
            'application_date'     =>date('d,M-Y',strtotime($this->created_at))

        ];
    }
}
