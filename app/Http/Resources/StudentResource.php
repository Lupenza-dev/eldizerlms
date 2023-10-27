<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           // 'customer'       =>new CustomerResource($this->customer),
            'college_name'   =>$this->college?->name,
            'position'       =>$this->position,
            'study_year'     =>$this->study_year,
            'student_reg_id' =>$this->student_reg_id,
            'heslb_status'   =>$this->heslb_status == 1 ? "Yes" : "No",
            'course'         =>$this->course,
        ];
    }
}
