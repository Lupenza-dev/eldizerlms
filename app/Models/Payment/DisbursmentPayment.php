<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Loan\LoanContract;

class DisbursmentPayment extends Model
{
    use HasFactory;

    protected $fillable=['loan_contract_id','loan_application_id','payment_reference','paid_amount','payment_date',
    'remark','payment_channel','payment_method','created_by','uuid'];

    public function loan_contract(){
        return $this->belongsTo(LoanContract::class);
    }
}
