<?php

namespace App\Imports;

use App\Models\Management\HESLBBeneficary;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Str;

class StudentImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $collection
    */
    private $college_id;

    public function __construct($college_id)
    {
        $this->college_id =$college_id;
    }

    public function model(array $collection)
    {
        return HESLBBeneficary::updateOrCreate([
            'full_name'     =>ucwords(strtolower($collection['full_name'])),
            'index_number'  =>$collection['index_number'],
            'code'          =>$collection['code'],
            'course_code'   =>$collection['course_code'],
            'reg_no'        =>$collection['rno'],
            'college_id'    =>$this->college_id
        ],[
            'year_of_study' =>$collection['yos'],
            'academic_year' =>$collection['academic_year'],
            'uuid' =>(string)Str::orderedUuid(),
        ]);
    }
}
