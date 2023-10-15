<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'request_amount'       =>number_format($this->amount),
            'loan_amount'          =>number_format($this->loan_amount),
            'contract_code'        =>$this->contract_code,
            'plan'                 =>$this->plan,
            'installment_amount'   =>number_format($this->installment_amount),
            'interest_rate'        =>$this->interest_rate,
            'interest_amount'      =>number_format($this->interest_amount),
            'loan_start_date'      =>date('d,M-Y',strtotime($this->start_date)),
            'loan_end_date'        =>date('Y-m-d', strtotime("+".$this->plan." months", strtotime($this->created_at))),
            'current_balance'      =>number_format($this->current_balance),
            'outstanding_amount'   =>number_format(($this->outstanding_amount)),
            'agent_name'           =>$this->loan_approval?->agent?->name,
            'agent_phone'          =>$this->loan_approval?->agent?->phone_number,
            'college'              =>$this->college?->name,
            'attended_date'        =>$this->loan_approval?->attended_date,
            'guarantor_name'       =>$this->guarantor?->full_name,
            'guarantor_phone'      =>$this->guarantor?->user?->phone_number,
            'guarantor_relation'   =>$this->guarantor?->relationship,
            'admin_remark'         =>$this->remark
        ];
    }
}
