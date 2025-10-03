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
            'customer'       =>new CustomerResource($this->customer),
            'amount'         =>number_format($this->amount),
            'loan_amount'    =>number_format($this->loan_amount),
            'plan'           =>"3 Months",
            // 'plan'           =>$this->plan,
            'installment_amount'   =>number_format($this->installment_amount),
            'interest_rate'        =>($this->interest_rate * 100).'%',
            'interest_amount'      =>number_format($this->interest_amount),
            'fees_amount'          =>number_format($this->fees_amount),
            'level'                =>$this->level,
            'loan_code'            =>$this->loan_code,
            'application_date'     =>date('d,M-Y',strtotime($this->created_at)),
            // 'loan_end_date'        =>date('d,M-Y', strtotime("+"3" months", strtotime($this->created_at))),
            'loan_end_date'        => date('d,M-Y', strtotime("+3 months", strtotime($this->created_at))),
            'has_contract'         =>$this->loan_contract ? "Yes" :"No",
            'contract'             =>new LoanContractResource($this->loan_contract),
            'agent'                =>$this->loan_approval?->agent?->name,
            'agent_phone'          =>$this->loan_approval?->agent?->phone_number,
            'status'               =>$this->loan_approval?->status,
            'agent_remark'         =>$this->loan_approval?->remark,
            'remark'               =>$this->remark,
            'agent_attended_date'  =>$this->loan_approval?->attended_date ? date('d,M-Y',strtotime($this->loan_approval?->attended_date)) : "Not Attendeded",
            'device_name'         =>$this->get_device?->name,
            'fee_charges' =>$this->fee_charges,
            'late_charges' =>$this->late_charges


        ];
    }
}
