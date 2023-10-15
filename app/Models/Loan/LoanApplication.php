<?php

namespace App\Models\Loan;

use App\Models\Management\College;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\Customer;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable=['customer_id','college_id','amount','loan_amount','plan','installment_amount','interest_rate','interest_amount','fees_amount',
    'level','loan_code','uuid','start_date'];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function loan_contract(){
        return $this->hasOne(LoanContract::class,'loan_application_id','id');
    }

    public function loan_approval(){
        return $this->hasOne(LoanApproval::class);
    }

    public function college(){
        return $this->hasOne(College::class,'id','college_id');
    }

    public function scopeWithFilters($query,$request){
        
        $start_date        =$request['application_start_date'] ?? null;
        $end_date          =$request['application_end_date'] ?? null;
        $college_id        =$request['college_id'] ?? null;
        $contract_status   =$request['status'] ?? null;
    
        return $query->when($start_date,function($query) use ($start_date,$end_date){
            if ($start_date != null || $end_date != null) {
                if ($start_date != null && $end_date != null)
                    $query->whereBetween('created_at', [$start_date, $end_date]);
    
                else if ($start_date != null)
                    $query->where('created_at', '>=', $start_date);
    
                else if ($end_date != null)
                    $query->where('created_at', '<=', $end_date);
            }
            })
            ->when($contract_status,function($query) use ($contract_status){
                $query->where('level',$contract_status);
            })
            ->when($college_id,function($query) use ($college_id){
                $query->where('college_id',$college_id);
            });
           
           
      }
}
