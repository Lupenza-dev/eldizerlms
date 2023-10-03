<?php
namespace App\Traits;
use Rap2hpoutre\FastExcel\FastExcel;


trait CustomerTrait
{
    public function exportCustomerReport($customers){
        return (new FastExcel($this->dataGenerator($customers)))->download('CustomerReport.xlsx',function($customer){
            
            return [
            'Register Date'  =>$customer->created_at,
            'Full name'      =>ucwords($customer?->first_name.' '.$customer?->middle_name.' '.$customer?->last_name),
            'Other Name'     =>ucwords($customer?->other_name),
            'Phone Number'   =>$customer?->phone_number,
            'Gender'         =>$customer?->gender?->name,
            'ID Number'      =>$customer?->id_number,
            'DOB'            =>$customer?->dob,
            'Email'          =>$customer?->email,
            'Region'         =>$customer->region?->name,
            'District'       =>$customer?->district?->name,
            'Ward'           =>$customer?->ward?->name,
            'Street'         =>$customer?->street,
            'Residence Since'     =>$customer?->resident_since,
            'College'             =>$customer->student?->college?->name,
            'College Location'    =>$customer->student?->college?->location,
            'Student Study Year'  =>$customer->student?->study_year,
            'Student Reg ID'      =>$customer->student?->customer->_reg_id,
            'Student Course'      =>$customer->student?->course,
            ];
        });
    }

    function dataGenerator($loans) {
        foreach ($loans as $loan) {
            yield $loan;
        }
    }
}
