<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HESLBBeneficary extends Model
{
    use HasFactory,SoftDeletes;

   public  $table ='heslb_beneficiaries';
   public $fillable =['full_name','index_number','code','course_code','reg_no','college_id','year_of_study','academic_year','uuid'];
}
