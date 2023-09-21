<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Loan\LoanContract;

class Payment extends Model
{
    use HasFactory;

    protected $fillable=['phone_number','amount','payment_reference','loan_contract_id','customer_id','remarks','uuid','added_by','payment_channel','payment_method','payment_date','status'];

    public function loan_contract(){
        return $this->hasOne(LoanContract::class,'id','loan_contract_id');
    }
}
