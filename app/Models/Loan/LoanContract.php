<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\Customer;
use App\Models\Management\Student;
use App\Models\Management\College;
use App\Models\Payment\Payment;

class LoanContract extends Model
{
    use HasFactory;

    protected $fillable=['loan_application_id','college_id','customer_id','amount','loan_amount','plan','installment_amount','interest_rate','interest_amount','fees_amount',
    'status','current_balance','contract_code','created_by','uuid','outstanding_amount','start_date','expected_end_date','other_fees'];

  public function customer(){
    return $this->belongsTo(Customer::class);
  }

  public function loan_approval(){
    return $this->hasOne(LoanApproval::class,'loan_application_id','loan_application_id');
  }

  public function installments(){
    return $this->hasMany(Installment::class);
  }

  public function payments(){
    return $this->hasMany(Payment::class);
  }

  public function student(){
    return $this->hasOne(Student::class,'customer_id','customer_id');
  }

  public function college(){
    return $this->hasOne(College::class,'id','college_id');
  }

  public function guarantor(){
    return $this->hasOne(Guarantor::class,'loan_application_id','loan_application_id');
  }

  public function guarantors(){
    return $this->hasMany(Guarantor::class,'loan_application_id','loan_application_id');
  }

  public function scopeWithFilters($query,$request){
        
    $start_date        =$request['start_date'] ?? null;
    $end_date          =$request['end_date'] ?? null;
    $contract_status   =$request['contract_status'] ?? null;
    $college_id        =$request['college_id'] ?? null;
    $past_due_days     =$request['past_due_days'] ?? null;
    $contract_code     =$request['contract_code'] ?? null;
    $past_due           =$request['past_due'] ?? null;

    return $query->when($start_date,function($query) use ($start_date,$end_date){
        if ($start_date != null || $end_date != null) {
            if ($start_date != null && $end_date != null)
                $query->whereBetween('start_date', [$start_date, $end_date]);

            else if ($start_date != null)
                $query->where('start_date', '>=', $start_date);

            else if ($end_date != null)
                $query->where('start_date', '<=', $end_date);
        }
        })
        ->when($contract_status,function($query) use ($contract_status){
            $query->where('status',$contract_status);
        })
        ->when($contract_code,function($query) use ($contract_code){
            $query->where('contract_code',$contract_code);
        })
        ->when($college_id,function($query) use ($college_id){
            $query->where('college_id',$college_id);
        })
        ->when($past_due,function($query) use ($past_due){
            if ($past_due == "0-30") {
                $query->whereBetween('past_due_days',[0,30]);
            }elseif ($past_due == "31-60") {
                $query->whereBetween('past_due_days',[31,60]);
            }elseif ($past_due == "61-90") {
                $query->whereBetween('past_due_days',[61,90]);
            }
            elseif ($past_due == "90 +") {
                $query->whereBetween('past_due_days','>',90);
            }
        });
       
  }

  public function getStatusFormattedAttribute(){
    switch ($this->status) {
      case 'GRANTED':
        $label ="<span class='badge bg-info text-white'>GRANTED</span>";
        break;
      case 'CLOSED':
        $label ="<span class='badge bg-success text-white'>CLOSED</span>";
        break;
      default:
      $label ="<span class='badge bg-info text-white'>GRANTED</span>";
        break;
    }

    return $label;
  }

  public function getFeeChargesAttribute(){
    $fees =json_decode($this->other_fees,true);
    return ($fees['fees_and_charges'] * 100).'%';
  }

  public function getLateChargesAttribute(){
    $fees =json_decode($this->other_fees,true);
    return ($fees['late_payment'] * 100).'%';
  }

}
