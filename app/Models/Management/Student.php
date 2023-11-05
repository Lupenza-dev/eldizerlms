<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable=['customer_id','form_four_index_no','college_id','position','study_year','student_reg_id','heslb_status','course','uuid'];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    
    public function college(){
        return $this->belongsTo(College::class);
    }

    public function agents (){
        return $this->hasMany(Agent::class,'college_id','college_id');
    }

    public function scopeWithFilters($query,$request){
        
        $student_reg_id  =$request['student_reg_id'] ?? null;
        $college_id  =$request['college_id'] ?? null;
        return $query->when($student_reg_id,function($query) use ($student_reg_id){
                $query->where('student_reg_id','like','%'.$student_reg_id.'%');
                })
                ->when($college_id,function($query) use ($college_id){
                    $query->where('college_id',$college_id);
                });
    }

}
