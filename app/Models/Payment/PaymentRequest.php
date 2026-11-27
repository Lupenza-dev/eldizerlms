<?php

namespace App\Models\Payment;

use App\Models\Loan\LoanContract;
use App\Models\Management\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'payment_requests';

    protected $fillable = [
        'uuid',
        'phone_number',
        'amount',
        'payment_reference',
        'payment_method',
        'payment_channel',
        'payment_date',
        'loan_contract_id',
        'customer_id',
        'added_by',
        'remarks',
        'status',
    ];

    public function loan_contract()
    {
        return $this->belongsTo(LoanContract::class, 'loan_contract_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
