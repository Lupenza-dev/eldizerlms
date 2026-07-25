<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandatePaymentCollection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'mandate_reference',
        'installment_order',
        'installment_amount',
        'min_installment_amount',
        'max_installment_amount',
        'current_balance',
        'outstanding_amount',
        'payment_date',
        'last_paid_amount',
        'reference',
        'status',
        'remarks',
    ];
}
