<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable=['loan_contract_id','installment_order','total_amount','current_balance','outstanding_amount','payment_type',
    'payment_date','due_days','past_due_amount','penalt_amount','penalt_amount_paid','last_paid_amount','status','uuid'];
}
