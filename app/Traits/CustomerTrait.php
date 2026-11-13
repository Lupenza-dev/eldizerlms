<?php

namespace App\Traits;

use Rap2hpoutre\FastExcel\FastExcel;

trait CustomerTrait
{
    public function exportCustomerReport($customers, $customerType = null)
    {
        return (new FastExcel($this->dataGenerator($customers)))
            ->download('CustomerReport.xlsx', function ($customer) use ($customerType) {

                // ═══ Common columns (kila customer) ═══
                $row = [
                    'Register Date'  => $customer->created_at,
                    'Full Name'      => ucwords(trim(
                                            ($customer->first_name ?? '') . ' ' .
                                            ($customer->middle_name ?? '') . ' ' .
                                            ($customer->last_name ?? '')
                                        )),
                    'Other Name'     => $customer->other_name ? ucwords($customer->other_name) : null,
                    'Phone Number'   => $customer->phone_number,
                    'Gender'         => $customer->gender?->name,
                    'ID Number'      => $customer->id_number,
                    'DOB'            => $customer->dob,
                    'Email'          => $customer->email,
                    'Customer Type'  => $customer->customer_reg_type,
                    'Region'         => $customer->region?->name,
                    'District'       => $customer->district?->name,
                    'Ward'           => $customer->ward?->name,
                    'Street'         => $customer->street,
                    'Residence Since'=> $customer->resident_since,
                ];

                // ═══ Student columns (tu kama filter = Student au Mixed) ═══
                $studentColumns = [
                    'College'             => $customer->student?->college?->name,
                    'College Location'    => $customer->student?->college?->location,
                    'Student Study Year'  => $customer->student?->study_year,
                    'Student Reg ID'      => $customer->student?->student_reg_id,
                    'Student Course'      => $customer->student?->course,
                ];

                // ═══ Intern columns (tu kama filter = Intern au Mixed) ═══
                $internColumns = [
                    'Hospital'           => $customer->intern?->hospital?->name,
                    'Professional'       => $customer->intern?->professional,
                    'Intern Start Date'  => $customer->intern?->start_date,
                    'Intern End Date'    => $customer->intern?->end_date,
                ];

                // ═══ Chagua columns kulingana na filter ═══
                if ($customerType == 1) {
                    return array_merge($row, $studentColumns);
                }

                if ($customerType == 2) {
                    return array_merge($row, $internColumns);
                }

                return array_merge($row, $studentColumns, $internColumns);
            });
    }

    private function dataGenerator($customers)
    {
        foreach ($customers as $customer) {
            yield $customer;
        }
    }
}