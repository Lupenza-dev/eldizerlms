<?php

namespace App\Models\Payment;

use App\Models\Management\CustomerMandate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMandate extends Model
{
    use HasFactory;
    protected $fillable = [
        'channel',
        'reference',
        'periodicity',
        'debit_type',
        'installment_amount',
        'min_installment_amount',
        'max_installment_amount',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'number_of_installment',
        'start_date',
        'end_date',
        'contract_status',
        'approved',
        'lifecycle_status',
        'remarks',
    ];

    public function customer_mandate(){
        return $this->hasOne(CustomerMandate::class,'mandate_reference','reference');
    }
}
