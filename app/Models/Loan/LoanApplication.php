<?php

namespace App\Models\Loan;

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

    public function loan_approval(){
        return $this->hasOne(LoanApproval::class);
    }
}
